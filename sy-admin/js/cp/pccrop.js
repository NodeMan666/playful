	function doCropping(newWidth,newHeight,x1,y1,x2,y2,minWidth,minHeight,fieldPre)  { 
		loadExample( 'dynamic' ,newWidth,newHeight,x1,y1,x2,y2,minWidth,minHeight,fieldPre);
		//document.getElementById('imgCrop_overlay').className='';
	}

function saveCoords (coords, dimensions)
{
			$( 'x1' ).value = coords.x1;
			$( 'y1' ).value = coords.y1;
			$( 'width' ).value = dimensions.width;
			$( 'height' ).value = dimensions.height;
}

		/**
		 * A little manager that allows us to swap the image dynamically
		 * for the dynamic example
		 *
		 */
		var CropImageManager = {
			/**
			 * Holds the current Cropper.Img object
			 * @var obj
			 */
			curCrop: null,
			
			/**
			 * Initialises the cropImageManager
			 *
			 * @access public
			 * @return void
			 */
			init: function() {
				this.attachCropper();
			},
			
			/**
			 * Handles the changing of the select to change the image, the option value
			 * is a pipe seperated list of imgSrc|width|height
			 * 
			 * @access public
			 * @param obj event
			 * @return void
			 */
			onChange: function( e ) {
				var vals = $F( Event.element( e ) ).split('|');
				this.setImage( vals[0], vals[1], vals[2] ); 
			},
			
			/**
			 * Sets the image within the element & attaches/resets the image cropper
			 *
			 * @access private
			 * @param string Source path of new image
			 * @param int Width of new image in pixels
			 * @param int Height of new image in pixels
			 * @return void
			 */
			setImage: function( imgSrc, w, h ) {
				$( 'theImageCrop' ).src = imgSrc;
				$( 'theImageCrop' ).width = w;
				$( 'theImageCrop' ).height = h;
				this.attachCropper();
			},
			
			/** 
			 * Attaches/resets the image cropper
			 *
			 * @access private
			 * @return void
			 */
			attachCropper: function() {
				if( this.curCrop == null ) this.curCrop = new Cropper.Img( 'theImageCrop', { onEndCrop: onEndCrop } );
				else this.curCrop.reset();
			},
			
			/**
			 * Removes the cropper
			 *
			 * @access public
			 * @return void
			 */
			removeCropper: function() {
				if( this.curCrop != null ) {
					this.curCrop.remove();
				}
			},
			
			/**
			 * Resets the cropper, either re-setting or re-applying
			 *
			 * @access public
			 * @return void
			 */
			resetCropper: function() {
				this.attachCropper();
			}
		};


		// setup the callback function
		function onEndCrop( coords, dimensions ) {
			$( 'x1' ).value = coords.x1;
			$( 'y1' ).value = coords.y1;
			$( 'width' ).value = dimensions.width;
			$( 'height' ).value = dimensions.height;
		}



		function loadExample( type,newWidth,newHeight,x1,y1,x2,y2,minWidth,minHeight,fieldPre ) {
			switch( type ) {
				case( 'ratioFourThree' ) :
					new Cropper.Img( 'theImageCrop', { ratioDim: { x: 220, y: 165 }, displayOnInit: true, onEndCrop: onEndCrop } );
					break;

				case( 'dynamic' ) :
					newMinWidth = minWidth*1;
					newMinHeight = minHeight*1;
					newNewWidth = newWidth*1;
					newNewHeight = newHeight*1;

					newx1 = x1*1;
					newx2 = x2*1;
					newy1 = y1*1;
					newy2 = y2*1;

					CropImageManager.init();
					new Cropper.Img( 'theImageCrop', { minWidth: newMinWidth, minHeight: newMinHeight, ratioDim: { x:newNewWidth, y: newNewHeight},displayOnInit: true, onloadCoords: { x1: newx1, y1: newy1, x2: newx2, y2: newy2 }, onEndCrop: saveCoords } );


					Event.observe( $('removeCropper'), 'click', CropImageManager.removeCropper.bindAsEventListener( CropImageManager ), false );
					Event.observe( $('resetCropper'), 'click', CropImageManager.resetCropper.bindAsEventListener( CropImageManager ), false );
					break;
				case( 'basic' ) :
				default :
					new Cropper.Img( 'theImageCrop', { onEndCrop: onEndCrop, displayOnInit: false} );
			}
		}