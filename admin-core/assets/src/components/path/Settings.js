import Icons from '@Admin/components/Icons';
import GeneralSettings from '@Admin/components/tabs/GeneralSettings';
import StylingSettings from '@Admin/components/tabs/StylingSettings';

function classNames( ...classes ) {
	return classes.filter( Boolean ).join( ' ' );
}

function Settings( props ) {
	const { navigation, tab, navigate } = props;
	return (
		<main className="max-w-[77rem] mr-[20px] mt-[2.5rem] bg-white shadow 2xl:mx-auto rounded-[0.2rem]">
			<div className="lg:grid lg:grid-cols-12 lg:gap-x-8">
				<aside className="py-6 px-2 ml-8 sm:px-6 lg:py-6 lg:px-0 lg:col-span-3 border-r">
					<nav className="space-y-1">
						{ navigation.map( ( item ) => (
							<a // eslint-disable-line
								key={ item.name }
								className={ classNames(
									tab === item.slug
										? 'bg-gray-50 text-wpcolor fill-wpcolor'
										: 'text-gray-900 fill-gray-900 hover:text-gray-900 hover:bg-gray-50',
									'group cursor-pointer rounded-[0.2rem] p-3 flex items-center text-sm font-medium'
								) }
								onClick={ () => {
									navigate( item.slug );
								} }
							>
								<span className="pr-2">
									{ Icons[ item.slug ] }
								</span>
								<span className="truncate">{ item.name }</span>
							</a>
						) ) }
					</nav>
				</aside>
				<div className="mb-0 sm:px-6 lg:px-0 lg:col-span-9">
					{ 'FRAKTJAKT_SHIPPING_FOR_DOKAN_setting' === tab && (
						<>
							<GeneralSettings />
						</>
					) }
					{ 'FRAKTJAKT_SHIPPING_FOR_DOKAN_styling' === tab && (
						<>
							<StylingSettings />
						</>
					) }
				</div>
			</div>
		</main>
	);
}

export default Settings;
