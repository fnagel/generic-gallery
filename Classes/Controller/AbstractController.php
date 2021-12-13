<?php

namespace FelixNagel\GenericGallery\Controller;

/**
 * This file is part of the "generic_gallery" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use FelixNagel\GenericGallery\Domain\Model\GalleryCollection;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Http\ImmediateResponseException;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use FelixNagel\GenericGallery\Domain\Repository\GalleryItemRepository;
use TYPO3\CMS\Frontend\Controller\ErrorController;

/**
 * BaseController.
 */
abstract class AbstractController extends ActionController
{
    /**
     * @var string
     */
    public const GALLERY_TYPE_SINGLE = 'single';

    /**
     * @var string
     */
    public const GALLERY_TYPE_IMAGES = 'images';

    /**
     * @var string
     */
    public const GALLERY_TYPE_COLLECTION = 'collection';

    /**
     * @var null
     */
    protected $uid = null;

    protected array $cObjData = [];

    protected array $gallerySettings = [];

    protected array $currentSettings = [];

    protected ?string $galleryKey = null;

    protected ?string $galleryType = null;

    protected ?string $template = null;

    /**
     * GalleryCollection.
     */
    protected ?GalleryCollection $collection = null;

    /**
     * Object manager.
     *
     * @TYPO3\CMS\Extbase\Annotation\Inject
     *
     * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     */
    protected $objectManager = null;

    /**
     * Construct class.
     */
    public function __construct()
    {
        $this->collection = new GalleryCollection();
    }

    /**
     * Get current plugin CE's uid.
     *
     * @return int
     */
    protected function getContentElementUid()
    {
        if ($this->uid === null) {
            $data = $this->getContentElementData();
            $this->uid = $data['_LOCALIZED_UID'] ?: $data['uid'];
        }

        return (int) $this->uid;
    }

    /**
     * Get current plugin CE's data.
     *
     * Note: getContentObject is marked as deprecated in "Scan Extension Files" but is to be available
     * in TYPO3 9.5, in 10.4 and 11.5.
     *
     * @return array
     */
    protected function getContentElementData()
    {
        return $this->configurationManager->getContentObject()->data;
    }

    /**
     * {@inheritdoc}
     */
    protected function initializeView(ViewInterface $view)
    {
        $this->view->assignMultiple([
            'uid' => $this->getContentElementUid(),
            'data' => [
                'content' => $this->getContentElementData(),
                'page' => $this->getTypoScriptFrontendController()->page,
                'pageLayout' => $this->getTypoScriptFrontendController()->cObj->getData(
                    'levelfield : -1 , layout, slide'
                ),
                'pageBackendLayout' => $this->getTypoScriptFrontendController()->cObj->getData('pagelayout'),
            ],
            'galleryType' => $this->galleryType,
            'gallerySettings' => $this->currentSettings,
        ]);

        if ($this->template !== '') {
            $template = GeneralUtility::getFileAbsFileName($this->template);

            if ($template !== '') {
                $view->setTemplatePathAndFilename($template);
            } else {
                $this->logError('Template for settings.gallery.'.$this->galleryKey.' not found!');
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function initializeAction()
    {
        $this->cObjData = $this->getContentElementData();
        $this->gallerySettings = $this->settings['gallery'];
        $this->galleryKey = rtrim($this->cObjData['tx_generic_gallery_predefined'], '.');
        $this->currentSettings = $this->gallerySettings[$this->galleryKey];

        $this->determineGalleryType();
        $this->generateCollection();
    }

    /**
     * Generate collection item.
     */
    protected function generateCollection()
    {
        switch ($this->galleryType) {
            case self::GALLERY_TYPE_SINGLE:
                $this->collection->addAllFromArray($this->getSigleItems());
                break;

            case self::GALLERY_TYPE_IMAGES:
                $this->collection->addAllFromFiles($this->getMultipleImages());
                break;

            case self::GALLERY_TYPE_COLLECTION:
                $this->collection->addAllFromFiles($this->getCollection());
                $this->collection->removeNonImageFiles();
                break;
        }
    }

    /**
     * Determine gallery type.
     */
    protected function determineGalleryType()
    {
        if ($this->cObjData['tx_generic_gallery_collection']) {
            $this->setGalleryType(self::GALLERY_TYPE_COLLECTION);

            return;
        }

        if ($this->cObjData['tx_generic_gallery_images']) {
            $this->setGalleryType(self::GALLERY_TYPE_IMAGES);

            return;
        }

        if ($this->cObjData['tx_generic_gallery_items']) {
            $this->setGalleryType(self::GALLERY_TYPE_SINGLE);

            return;
        }
    }

    /**
     * Set gallery tpye.
     *
     * @param string $key
     */
    protected function setGalleryType($key)
    {
        $this->galleryType = $key;
    }

    /**
     * Method to get the image data from one FCE.
     *
     * @return array
     */
    protected function getSigleItems()
    {
        /* @var $itemRepository GalleryItemRepository */
        $itemRepository = $this->objectManager->get(GalleryItemRepository::class);

        return $itemRepository->findForContentElement($this->getContentElementUid())->toArray();
    }

    /**
     * @return array
     */
    protected function getMultipleImages()
    {
        /* @var $fileRepository FileRepository */
        $fileRepository = $this->objectManager->get(FileRepository::class);

        return $fileRepository->findByRelation(
            'tt_content',
            'tx_generic_gallery_picture_single',
            $this->getContentElementUid()
        );
    }

    /**
     * @return array
     */
    protected function getCollection()
    {
        /* @var $resourceFactory ResourceFactory */
        $resourceFactory = $this->objectManager->get(ResourceFactory::class);

        /* @var $collection \TYPO3\CMS\Core\Resource\Collection\AbstractFileCollection */
        $collection = $resourceFactory->getCollectionObject((int) $this->cObjData['tx_generic_gallery_collection']);
        $collection->loadContents();

        return $collection->getItems();
    }

    /**
     * Log error.
     *
     * @param string $message Error message
     * @param int    $error   Error level. 0 = message, 1 = error (user problem), 2 = System Error (which should not happen), 3 = security notice (admin)
     */
    protected function logError($message = '', $error = 2)
    {
        /* @var $backendUserAuthentication BackendUserAuthentication */
        $backendUserAuthentication = GeneralUtility::makeInstance(BackendUserAuthentication::class);
        $backendUserAuthentication->writelog(4, 0, $error, 0, '[tx_generic_gallery] Error: '.$message, []);
    }

    /**
     * Note: getContentObject is marked as deprecated in "Scan Extension Files" but is available used like this
     *
     * @param string $message
     */
    protected function pageNotFoundAndExit($message = 'Image not found!')
    {
        $response = GeneralUtility::makeInstance(ErrorController::class)->pageNotFoundAction(
            $GLOBALS['TYPO3_REQUEST'],
            $message
        );

        throw new ImmediateResponseException($response, 1576748646637);
    }

    /**
     * @return \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
     */
    protected function getTypoScriptFrontendController()
    {
        return $GLOBALS['TSFE'];
    }
}
