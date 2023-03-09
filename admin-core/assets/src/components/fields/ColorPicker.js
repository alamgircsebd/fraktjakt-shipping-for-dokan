import { useState, useRef, useEffect } from 'react';
import { __ } from '@wordpress/i18n';
import reactCSS from 'reactcss';
import { useStateValue } from '@Admin/components/Data';
import { debounce } from 'lodash';
import { SketchPicker } from 'react-color';

function ColorPicker( props ) {
	const { name, value, defaultColor } = props;
	const [ data, dispatch ] = useStateValue();
	const [ displayColorPicker, setdisplayColorPicker ] = useState( false );
	const [ color, setColor ] = useState( value );

	const debounceDispatch = useRef(
		debounce( async ( dispatchParams ) => {
			dispatch( dispatchParams );
		}, 500 )
	).current;

	useEffect( () => {
		return () => {
			debounceDispatch.cancel();
		};
	}, [ debounceDispatch ] );

	const styles = reactCSS( {
		default: {
			color: {
				width: '36px',
				height: '30px',
				background: color,
			},
		},
	} );

	const handleClick = () => {
		setdisplayColorPicker( ( prevValue ) => ! prevValue );
	};
	const handleClose = () => {
		setdisplayColorPicker( false );
	};
	const handleResetColor = () => {
		handleChange( { hex: defaultColor } );
	};

	const handleChange = ( newcolor ) => {
		if ( newcolor ) {
			setColor( newcolor.hex );
		} else {
			setColor( newcolor );
		}

		// Trigger change
		const changeEvent = new CustomEvent(
			'FraktjaktShippingForDokan:color:change',
			{
				bubbles: true,
				detail: {
					e: 'color',
					name: props.name,
					value: newcolor ? newcolor.hex : newcolor,
				},
			}
		);

		document.dispatchEvent( changeEvent );

		let change = false;
		const newData = data;
		const elements = name.split( /[\[\]]/ );

		if (
			data[ elements[ 0 ] ][ elements[ 1 ] ] !== newcolor
				? newcolor.hex
				: newcolor
		) {
			newData[ elements[ 0 ] ][ elements[ 1 ] ] = newcolor
				? newcolor.hex
				: newcolor;
			change = true;
		}

		if ( change ) {
			debounceDispatch( {
				type: 'CHANGE',
				data: newData,
			} );
		}
	};

	return (
		<>
			<div className="fraktjakt-shipping-for-dokan-field-data-content">
				<div className="fraktjakt-shipping-for-dokan-colorpicker-selector justify-end">
					<div
						className="fraktjakt-shipping-for-dokan-colorpicker-swatch-wrap"
						onClick={ handleClick }
					>
						<span
							className="fraktjakt-shipping-for-dokan-colorpicker-swatch"
							style={ styles.color }
						/>
						<span className="fraktjakt-shipping-for-dokan-colorpicker-label">
							{ __( 'Select Color', 'fraktjakt-shipping-for-dokan' ) }
						</span>
						<input type="hidden" name={ name } value={ color } />
					</div>
					<span
						className="fraktjakt-shipping-for-dokan-colorpicker-reset"
						onClick={ handleResetColor }
						title={ __( 'Reset', 'fraktjakt-shipping-for-dokan' ) }
					>
						<span className="dashicons dashicons-update-alt"></span>
					</span>
				</div>
				<div className="fraktjakt-shipping-for-dokan-color-picker">
					{ displayColorPicker ? (
						<div className="fraktjakt-shipping-for-dokan-color-picker-popover">
							<div
								className="fraktjakt-shipping-for-dokan-color-picker-cover"
								onClick={ handleClose }
							/>
							<SketchPicker
								name={ name }
								color={ color }
								onChange={ handleChange }
								disableAlpha={ true }
							/>
						</div>
					) : null }
				</div>
			</div>
		</>
	);
}

export default ColorPicker;
