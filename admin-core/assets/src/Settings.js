import React from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter } from 'react-router-dom';
import reducer, { initialState } from './components/Reducer';

/* Main Compnent */
import '@Admin/Settings.scss';
import Container from '@Admin/components/Container';
import { StateProvider } from './components/Data';

ReactDOM.render(
	<BrowserRouter>
		<StateProvider initialState={ initialState } reducer={ reducer }>
			<Container />
		</StateProvider>
	</BrowserRouter>,
	document.getElementById( 'fraktjakt-shipping-for-dokan-settings' )
);
