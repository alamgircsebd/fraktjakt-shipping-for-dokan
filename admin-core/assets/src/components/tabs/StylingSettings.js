import { __ } from '@wordpress/i18n';
import SectionWrapper from '@Admin/components/wrappers/SectionWrapper';
import ColorField from '@Admin/components/fields/ColorField';
import { useStateValue } from '@Admin/components/Data';

function ShopSettings() {
	const [ data ] = useStateValue();

	return (
		<>
			<SectionWrapper heading={ __( 'Colors', 'fraktjakt-shipping-for-dokan' ) }>
				<ColorField
					title={ __( 'Primary color', 'fraktjakt-shipping-for-dokan' ) }
					description={ __(
						'Choose color for primary color.',
						'fraktjakt-shipping-for-dokan'
					) }
					name={
						'FRAKTJAKT_SHIPPING_FOR_DOKAN_appearance[primary_bg_color]'
					}
					value={
						data.FRAKTJAKT_SHIPPING_FOR_DOKAN_appearance.primary_bg_color
					}
					default={ '#ECECEE' }
				/>
				<ColorField
					title={ __(
						'Primary text color',
						'fraktjakt-shipping-for-dokan'
					) }
					description={ __(
						'Choose color for primary text color.',
						'fraktjakt-shipping-for-dokan'
					) }
					name={
						'FRAKTJAKT_SHIPPING_FOR_DOKAN_appearance[primary_font_color]'
					}
					value={
						data.FRAKTJAKT_SHIPPING_FOR_DOKAN_appearance.primary_font_color
					}
					default={ '#000000' }
				/>
			</SectionWrapper>
		</>
	);
}

export default ShopSettings;
