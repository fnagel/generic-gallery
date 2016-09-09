# Link validator config
mod.linkvalidator.searchFields {
	tx_generic_gallery_pictures = link
	tx_generic_gallery_content = bodytext
}

mod.wizards.newContentElement.wizardItems.plugins {
	elements.genericGallery {
		iconIdentifier = content-image
		title = LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xlf:generic_gallery.plugin.title
		description = LLL:EXT:generic_gallery/Resources/Private/Language/locallang_db.xlf:generic_gallery.plugin.description
		tt_content_defValues.CType = list
		tt_content_defValues.list_type = genericgallery_pi1
	}
}
