export const initialState = FRAKTJAKT_SHIPPING_FOR_DOKAN_settings;

const reducer = ( state, action ) => {
	switch ( action.type ) {
		case 'CHANGE':
			return {
				...action.data,
			};

		default:
			return state;
	}
};

export default reducer;
