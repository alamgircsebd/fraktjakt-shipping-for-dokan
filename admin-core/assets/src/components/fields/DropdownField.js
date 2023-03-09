import { Fragment, useState, useRef, useEffect } from 'react';
import { Listbox, Transition } from '@headlessui/react';
import { SelectorIcon } from '@heroicons/react/solid';
import { useStateValue } from '@Admin/components/Data';
import { debounce } from 'lodash';
import FieldWrapper from '@Admin/components/wrappers/FieldWrapper';

function DropdownField( props ) {
	const { title, description, name, value, optionsArray } = props;
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

	const dbValue = Object.keys( optionsArray ).find(
		( key ) => optionsArray[ key ].id === value
	);
	const [ selected, setSelected ] = useState( optionsArray[ dbValue ] );

	function handleOnChange( selectedValue ) {
		let change = false;
		const newData = data;
		const elements = name.split( /[\[\]]/ );
		if ( data[ elements[ 0 ] ][ elements[ 1 ] ] !== selectedValue ) {
			newData[ elements[ 0 ] ][ elements[ 1 ] ] = selectedValue;
			change = true;
		}

		if ( change ) {
			debounceDispatch( {
				type: 'CHANGE',
				data: newData,
			} );
		}
	}
	return (
		<FieldWrapper title={ title } description={ description }>
			<div>
				<Listbox
					name={ name }
					value={ selected }
					onChange={ setSelected }
				>
					<div className="relative mt-1 w-32">
						<Listbox.Button className="relative w-full py-2 pl-3 pr-10 text-left bg-white rounded-lg shadow-md cursor-default focus:outline-none focus-visible:ring-2 focus-visible:ring-opacity-75 focus-visible:ring-white focus-visible:ring-offset-orange-300 focus-visible:ring-offset-2 focus-visible:border-indigo-500 sm:text-sm">
							<span className="block truncate">
								{ selected.name }
							</span>
							<span className="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
								<SelectorIcon
									className="w-5 h-5 text-gray-400"
									aria-hidden="true"
								/>
							</span>
						</Listbox.Button>
						<Transition
							as={ Fragment }
							leave="transition ease-in duration-100"
							leaveFrom="opacity-100"
							leaveTo="opacity-0"
						>
							<Listbox.Options className="absolute w-full py-1 mt-1 z-40 overflow-auto text-base bg-white rounded-md shadow-lg max-h-60 ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
								{ optionsArray.map( ( options, id ) => (
									<Listbox.Option
										key={ id }
										className={ ( { active } ) =>
											`${
												active
													? ' text-white bg-wpcolor'
													: 'text-gray-900'
											}
									cursor-default select-none relative py-1 pl-4`
										}
										value={ options }
									>
										{ ( { active } ) => (
											<>
												<span
													className={ `${
														selected
															? 'font-medium'
															: 'font-normal'
													} block` }
												>
													{ options.name }
												</span>
												{ selected ? (
													<span
														className={ `${
															active
																? 'text-wpcolor'
																: 'text-wpcolor20'
														}
											absolute inset-y-0 left-0 flex items-center pl-3` }
													></span>
												) : null }
											</>
										) }
									</Listbox.Option>
								) ) }
							</Listbox.Options>
						</Transition>
					</div>
				</Listbox>
				<input
					type="hidden"
					name={ name }
					value={ selected.id }
					onChange={ handleOnChange( selected.id ) }
				/>
			</div>
		</FieldWrapper>
	);
}

export default DropdownField;
