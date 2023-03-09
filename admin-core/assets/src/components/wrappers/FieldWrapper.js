function FieldWrapper( props ) {
	const { children, title, description, badge, content } = props;

	return (
		<section className="flex border-b border-solid border-slate-200 mr-8 mt-8 last:border-b-0">
			{ ( title || description ) && (
				<div className="pr-16 pb-8 w-[78%]">
					{ title && (
						<h3 className="text-lg leading-6 font-medium text-gray-900">
							{ title }
						</h3>
					) }
					{ description && (
						<p className="mt-[0.6rem] text-sm ">
							{ description }
							{ badge && (
								<span className="inline-flex items-center ml-1 px-2.5 py-0.5 rounded-md text-xs font-medium bg-wpcolorfaded text-wpcolor">
									{ badge }
								</span>
							) }
						</p>
					) }
				</div>
			) }
			{ content && (
				<div className="pr-16 pb-8 w-full]">{ children }</div>
			) }

			{ ! content && children }
		</section>
	);
}

export default FieldWrapper;
