import { useState, useRef, useEffect } from 'react';
import { useStateValue } from '@Admin/components/Data';
import { debounce } from 'lodash';

function Number( props ) {
	const [ value, setValue ] = useState( props.val );
	const [ data, dispatch ] = useStateValue();

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

	function handleChange( event ) {
		setValue( event.target.value );

		const newData = data;
		const elements = props.name.split( /[\[\]]/ );

		newData[ elements[ 0 ] ][ elements[ 1 ] ] = event.target.value;
		debounceDispatch( { type: 'CHANGE', data: newData } );
	}

	return (
		<div className=" flex justify-end">
			<input
				type="number"
				name={ props.name }
				min={ props.min || 0 }
				value={ value }
				onChange={ handleChange }
				className={ `${ props.badge ? 'w-24 ' : 'w-32 rounded-r-md' }
				focus:ring-wpcolor focus:border-wpcolor px-8 sm:text-sm border-gray-300 rounded-l-md inline-flex bg-white text-gray-500 appearance-none  border-0 focus:outline-none focus:bg-white` }
			/>
		</div>
	);
}

export default Number;
