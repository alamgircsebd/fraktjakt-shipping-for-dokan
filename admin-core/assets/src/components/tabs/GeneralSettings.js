import React from 'react';
import { __ } from '@wordpress/i18n';
import SectionWrapper from '@Admin/components/wrappers/SectionWrapper';
import DropdownField from '@Admin/components/fields/DropdownField';
import TextField from '@Admin/components/fields/TextField';
import { useStateValue } from '@Admin/components/Data';
import NumberField from '@Admin/components/fields/NumberField';

function GeneralSettings() {
	const [ data ] = useStateValue();

	return (
		<>
			<SectionWrapper
				heading={ __( 'General', 'fraktjakt-shipping-for-dokan' ) }
			>
				<NumberField
					title={ __( 'Page per limit', 'fraktjakt-shipping-for-dokan' ) }
					description={ __(
						'Set books listing page per limit.',
						'fraktjakt-shipping-for-dokan'
					) }
					badge={ __( 'Default: 10', 'fraktjakt-shipping-for-dokan' ) }
					name={ 'FRAKTJAKT_SHIPPING_FOR_DOKAN_general[page_per_limit]' }
					max={ 100 }
					value={ data.FRAKTJAKT_SHIPPING_FOR_DOKAN_general.page_per_limit }
					type={ 'Limit' }
				/>
				<DropdownField
					title={ __( 'Books ordering (By Entry Date)', 'fraktjakt-shipping-for-dokan' ) }
					description={ __(
						'Choose a books ordering.',
						'fraktjakt-shipping-for-dokan'
					) }
					name={ 'FRAKTJAKT_SHIPPING_FOR_DOKAN_general[ordering]' }
					value={ data.FRAKTJAKT_SHIPPING_FOR_DOKAN_general.ordering }
					optionsArray={ [
						{
							id: 'ASC',
							name: __( 'ASC', 'fraktjakt-shipping-for-dokan' ),
						},
						{
							id: 'DESC',
							name: __( 'DESC', 'fraktjakt-shipping-for-dokan' ),
						},
					] }
				/>
				<DropdownField
					title={ __( 'Select Option', 'fraktjakt-shipping-for-dokan' ) }
					description={ __(
						'Choose a option multiple or single for Publisher and Author.',
						'fraktjakt-shipping-for-dokan'
					) }
					name={ 'FRAKTJAKT_SHIPPING_FOR_DOKAN_general[selection]' }
					value={ data.FRAKTJAKT_SHIPPING_FOR_DOKAN_general.selection }
					optionsArray={ [
						{
							id: 'single',
							name: __( 'Single', 'fraktjakt-shipping-for-dokan' ),
						},
						{
							id: 'multiple',
							name: __( 'Multiple', 'fraktjakt-shipping-for-dokan' ),
						},
					] }
				/>
				<TextField
					title={ __( 'Currency', 'fraktjakt-shipping-for-dokan' ) }
					description={ __(
						'Set currency for book price.',
						'fraktjakt-shipping-for-dokan'
					) }
					badge={ __( 'Default value: $', 'fraktjakt-shipping-for-dokan' ) }
					name={ 'FRAKTJAKT_SHIPPING_FOR_DOKAN_general[currency]' }
					value={ data.FRAKTJAKT_SHIPPING_FOR_DOKAN_general.currency }
				/>
			</SectionWrapper>
			<SectionWrapper
				heading={ __( 'Text Localizations', 'fraktjakt-shipping-for-dokan' ) }
			>
				<TextField
					title={ __( 'Main title', 'fraktjakt-shipping-for-dokan' ) }
					description={ __(
						'Set main title above search form.',
						'fraktjakt-shipping-for-dokan'
					) }
					badge={ __(
						'Default value: Book Search',
						'fraktjakt-shipping-for-dokan'
					) }
					name={ 'FRAKTJAKT_SHIPPING_FOR_DOKAN_general[main_title]' }
					value={ data.FRAKTJAKT_SHIPPING_FOR_DOKAN_general.main_title }
				/>
				<TextField
					title={ __( 'Button label', 'fraktjakt-shipping-for-dokan' ) }
					description={ __(
						'Set button label for search form.',
						'fraktjakt-shipping-for-dokan'
					) }
					badge={ __(
						'Default value: Search',
						'fraktjakt-shipping-for-dokan'
					) }
					name={ 'FRAKTJAKT_SHIPPING_FOR_DOKAN_general[button_label]' }
					value={ data.FRAKTJAKT_SHIPPING_FOR_DOKAN_general.button_label }
				/>
			</SectionWrapper>
		</>
	);
}

export default GeneralSettings;
