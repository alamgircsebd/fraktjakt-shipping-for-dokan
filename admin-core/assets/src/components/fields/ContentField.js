import FieldWrapper from '@Admin/components/wrappers/FieldWrapper';

function ContentField( props ) {
	const { children, content } = props;
	return <FieldWrapper content={ content }>{ children }</FieldWrapper>;
}

export default ContentField;
