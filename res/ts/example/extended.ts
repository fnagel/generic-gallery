
plugin.tx_genericgallery_pi1 {
	gallery {
		# this example configuration is not a demo and will not work out-of-the-box
		picture_gallery {
			name = Bildergalerie Test
			template = EXT:generic_gallery/res/templates/example/extended.html
			codeToFooter = 0
			
			# define marker, all DAM fields available, prefix IMAGE_ neded, please note predefined markers (IMAGE_WIDTH, IMAGE_HEIGHT)
			marker {
				# multiple fields possible as backfall
				IMAGE_DESCRIPTION = description // caption // alt_text // title
				IMAGE_ALT = alt_text // caption // alt_text // title
				IMAGE_EXIF_MODEL = exif_Model
				IMAGE_EXIF_DATE = exif_DateTime
				IMAGE_EXIF_VERSCHLUSS = exif_ExposureTime
				IMAGE_EXIF_ISO = exif_ISOSpeedRatings
				IMAGE_EXIF_BLENDE = exif_ApertureValue
			}
			
			ajax = 1
			dateFormat = %d.%m.%Y %H:%M
			
			image {
				maxW = 1280
				maxH = 800
			}
			thumb {
				1 {
					maxW = 540
					maxH = 540
				}
				2 {
					width = 40c-0
					height = 40c-0
				}
			}
			range {
				content = 1-1000
			}
			
				
			custom {
				1 {
					marker = ###FIRST_IMAGE###	
					index = 1
					content = ###IMAGE###
				}
				2 {
					marker = ###FIRST_IMAGE_HEIGHT###		
					index = 1
					content = ###THUMB_1_HEIGHT###
				}
				3 {
					marker = ###FIRST_IMAGE_DESCRIPTION###	
					index = 1
					content = ###IMAGE_DESCRIPTION###
				}
				4 {
					marker = ###FIRST_IMAGE_THUMB_1###		
					index = 1
					content = ###THUMB_1###
				}
			}
		}
	}
}