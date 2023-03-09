import { useState, useRef, useEffect } from 'react';
import { Switch } from '@headlessui/react';
import { useStateValue } from '@Admin/components/Data';
import { debounce } from 'lodash';
import FieldWrapper from '@Admin/components/wrappers/FieldWrapper';

function classNames( ...classes ) {
	return classes.filter( Boolean ).join( ' ' );
}

function ToggleField( props ) {
	const { title, description, name, value } = props;
	const [ data, dispatch ] = useStateValue();
	const [ enable, setEnable ] = useState( value );

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

	function handleOnChange( state ) {
		setEnable( ! state );
		if ( 'function' === typeof props.manageState ) {
			props.manageState( ! state );
		}

		const newData = data;
		const elements = name.split( /[\[\]]/ );

		newData[ elements[ 0 ] ][ elements[ 1 ] ] = ! state;

		debounceDispatch( {
			type: 'CHANGE',
			data: newData,
		} );
	}

	return (
		<FieldWrapper title={ title } description={ description }>
			<div>
				<Switch
					checked={ enable }
					value={ enable }
					name={ name }
					onChange={ () => {
						handleOnChange( enable );
					} }
					className={ classNames(
						enable ? 'bg-wpcolor' : 'bg-gray-200',
						'relative inline-flex flex-shrink-0 h-5 w-[2.4rem] items-center border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none'
					) }
				>
					<span className="sr-only"></span>
					<span
						aria-hidden="true"
						className={ classNames(
							enable ? 'translate-x-5' : 'translate-x-0',
							'pointer-events-none inline-block h-3.5 w-3.5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200'
						) }
					/>
				</Switch>
			</div>
		</FieldWrapper>
	);
}

export default ToggleField;
