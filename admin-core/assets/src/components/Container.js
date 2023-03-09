import React, { useState, useEffect, useRef } from 'react';
import { useLocation, useHistory } from 'react-router-dom';
import { __ } from '@wordpress/i18n';

import Notification from '@Admin/components/tabs/Notification';
import Header from '@Admin/components/Header';
import Settings from '@Admin/components/path/Settings';
import apiFetch from '@wordpress/api-fetch';
import { useStateValue } from '@Admin/components/Data';

function Container() {
	const [ data ] = useStateValue();
	const [ settingsTab, setSettingsTab ] = useState( '' );
	const query = new URLSearchParams( useLocation().search );
	const activePage = 'FRAKTJAKT_SHIPPING_FOR_DOKAN_settings';
	const activePath = 'settings';
	const tab = [
		'FRAKTJAKT_SHIPPING_FOR_DOKAN_setting',
		'FRAKTJAKT_SHIPPING_FOR_DOKAN_styling',
		'how',
	].includes( query.get( 'tab' ) )
		? query.get( 'tab' )
		: getSettingsTab();
	const [ processing, setProcessing ] = useState( false );

	const [ status, setStatus ] = useState( false );

	const updateData = useRef( false );

	useEffect( () => {
		if ( ! updateData.current ) {
			updateData.current = true;
			return;
		}

		const formData = new window.FormData();

		formData.append( 'action', 'FRAKTJAKT_SHIPPING_FOR_DOKAN_update_settings' );
		formData.append(
			'security',
			FRAKTJAKT_SHIPPING_FOR_DOKAN_settings.update_nonce
		);
		formData.append(
			'FRAKTJAKT_SHIPPING_FOR_DOKAN_general',
			JSON.stringify( data.FRAKTJAKT_SHIPPING_FOR_DOKAN_general )
		);
		formData.append(
			'FRAKTJAKT_SHIPPING_FOR_DOKAN_appearance',
			JSON.stringify( data.FRAKTJAKT_SHIPPING_FOR_DOKAN_appearance )
		);

		setProcessing( true );

		apiFetch( {
			url: FRAKTJAKT_SHIPPING_FOR_DOKAN_settings.ajax_url,
			method: 'POST',
			body: formData,
		} ).then( () => {
			setProcessing( false );
			setStatus( true );
			setTimeout( () => {
				setStatus( false );
			}, 2000 );
		} );
	}, [ data ] );

	const history = useHistory();
	const navigation = [
		{
			name: __( 'General Settings', 'fraktjakt-shipping-for-dokan' ),
			slug: 'FRAKTJAKT_SHIPPING_FOR_DOKAN_setting',
		},
	];

	navigation.push( {
		name: __( 'Styling', 'fraktjakt-shipping-for-dokan' ),
		slug: 'FRAKTJAKT_SHIPPING_FOR_DOKAN_styling',
	} );

	function navigate( navigateTab ) {
		setSettingsTab( navigateTab );
		history.push(
			'admin.php?page=FRAKTJAKT_SHIPPING_FOR_DOKAN_settings&path=settings&tab=' +
				navigateTab
		);
	}

	function getSettingsTab() {
		return settingsTab || 'FRAKTJAKT_SHIPPING_FOR_DOKAN_setting';
	}

	return (
		<form
			className="FraktjaktShippingForDokanSettings"
			id="FraktjaktShippingForDokanSettings"
			method="post"
		>
			<Header
				processing={ processing }
				activePage={ activePage }
				activePath={ activePath }
			/>
			<Notification status={ status } setStatus={ setStatus } />
			{ 'settings' === activePath ? (
				<Settings
					navigation={ navigation }
					tab={ tab }
					navigate={ navigate }
				/>
			) : (
				<Settings
					navigation={ navigation }
					tab={ tab }
					navigate={ navigate }
				/>
			) }
		</form>
	);
}

export default Container;
