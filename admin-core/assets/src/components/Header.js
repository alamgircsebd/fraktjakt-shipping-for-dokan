import { __ } from '@wordpress/i18n';
import { Link } from 'react-router-dom';
import Logo from '../../images/logo.svg';

const menus = [
	{
		name: __( 'Settings', 'fraktjakt-shipping-for-dokan' ),
		path: 'settings',
	},
];

function Header( props ) {
	const { processing, activePath } = props;

	return (
		<div className="sticky top-0 md:top-[32px] 600px:top-[46px] right-0 bg-white -ml-5 shadow z-10">
			<div className="relative flex justify-between h-16 max-w-3xl mx-auto px-8 lg:max-w-7xl">
				<div className="flex-1 flex items-center justify-center sm:items-stretch sm:justify-start">
					<span className="flex-shrink-0 flex items-center">
						<img
							className="lg:block h-[2.6rem] w-auto"
							src={ Logo }
							alt="Workflow"
						/>
					</span>
					<div className="sm:ml-8 sm:flex sm:space-x-8">
						{ menus.map( ( menu, key ) => (
							<Link
								index={ key }
								key={ `?page=FRAKTJAKT_SHIPPING_FOR_DOKAN_settings&path=${ menu.path }` }
								to={ {
									pathname: 'admin.php',
									search: `?page=FRAKTJAKT_SHIPPING_FOR_DOKAN_settings${
										'' !== menu.path
											? '&path=' + menu.path
											: ''
									}`,
								} }
								className={ `${
									activePath === menu.path
										? ' border-wpcolor hover:text-wphovercolor text-gray-900 inline-flex items-center px-1 border-b-2 text-[0.940rem] font-medium focus:shadow-none'
										: 'border-transparent  hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 border-b-2 text-[0.940rem] font-medium focus:shadow-none'
								}` }
							>
								{ menu.name }
							</Link>
						) ) }
					</div>
				</div>
				<div className="absolute right-0 flex items-center pr-2 -mr-5 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
					<button
						title={ __( 'Notification', 'fraktjakt-shipping-for-dokan' ) }
						className="w-10 h-10 mr-0 rounded-full flex items-center justify-center cursor-pointer bg-gray-300"
					>
						{ processing && (
							<svg
								className="FraktjaktShippingForDokan-animate-spin-reverse h-6 w-6"
								xmlns="http://www.w3.org/2000/svg"
								fill="none"
								viewBox="0 0 24 24"
								stroke="currentColor"
								strokeWidth="2"
							>
								<path
									strokeLinecap="round"
									strokeLinejoin="round"
									d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
								/>
							</svg>
						) }

						{ ! processing && (
							<svg
								width="24"
								height="24"
								viewBox="0 0 24 24"
								fill="none"
								xmlns="http://www.w3.org/2000/svg"
							>
								<path
									d="M11 5.88218V19.2402C11 20.2121 10.2121 21 9.24018 21C8.49646 21 7.83302 20.5325 7.58288 19.8321L5.43647 13.6829M18 13C19.6569 13 21 11.6569 21 10C21 8.34315 19.6569 7 18 7M5.43647 13.6829C4.0043 13.0741 3 11.6543 3 10C3 7.79086 4.79086 6 6.99999 6H8.83208C12.9327 6 16.4569 4.7659 18 3L18 17C16.4569 15.2341 12.9327 14 8.83208 14L6.99998 14C6.44518 14 5.91677 13.887 5.43647 13.6829Z"
									stroke="#111827"
									strokeWidth="2"
									strokeLinecap="round"
									strokeLinejoin="round"
								/>
							</svg>
						) }
					</button>
				</div>
			</div>
		</div>
	);
}

export default Header;
