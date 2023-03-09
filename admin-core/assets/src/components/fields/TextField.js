import Text from '@Admin/components/fields/Text';
import FieldWrapper from '@Admin/components/wrappers/FieldWrapper';

function TextField( props ) {
	const { title, description } = props;

	return (
		<FieldWrapper title={ title } description={ description }>
			<div>
				<Text
					name={ props.name }
					val={ props.value }
					max={ props.max }
				/>
			</div>
		</FieldWrapper>
	);
}

export default TextField;
