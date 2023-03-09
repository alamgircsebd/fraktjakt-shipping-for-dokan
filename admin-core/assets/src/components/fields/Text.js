import { useState } from 'react';
import { useStateValue } from '@Admin/components/Data';

function Text( props ) {
	const [ value, setValue ] = useState( props.val );
	const [ data, dispatch ] = useStateValue();

	function handleChange( event ) {
		setValue( event.target.value );

		const newData = data;
		const elements = props.name.split( /[\[\]]/ );

		newData[ elements[ 0 ] ][ elements[ 1 ] ] = event.target.value;
		dispatch( {
			type: 'CHANGE',
			data: newData,
		} );
	}
	return (
		<input
			type="text"
			name={ props.name }
			min="0"
			value={ value }
			max={ props.max }
			onChange={ handleChange }
			className="focus:ring-wpcolor focus:border-wpcolor block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
		/>
	);
}

export default Text;
