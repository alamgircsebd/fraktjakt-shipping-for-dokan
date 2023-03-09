import React from 'react';
import ColorPicker from '@Admin/components/fields/ColorPicker';
import FieldWrapper from '@Admin/components/wrappers/FieldWrapper';

function ColorField( props ) {
	const { title, description } = props;

	return (
		<FieldWrapper title={ title } description={ description }>
			<div className="fraktjakt-shipping-for-dokan-color-field">
				<ColorPicker
					name={ props.name }
					value={ props.value }
					defaultColor={ props.default }
				/>
			</div>
		</FieldWrapper>
	);
}

export default ColorField;
