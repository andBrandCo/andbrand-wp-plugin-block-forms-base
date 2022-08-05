import React from 'react';
import { ServerSideRender } from '@eightshift/frontend-libs/scripts';

//import { ServerSideRender } from "@wordpress/sever-side-render";

export const SubmitEditor = (attributes) => {
	const {
		blockFullName,
		clientId,
		prefix,
		setAttributes,
		...formattedAttributes

	} = attributes;

	return (
		<ServerSideRender
			block={blockFullName}
			attributes={{
				...formattedAttributes,
				submitUniqueId: clientId,
			}}
		/>
	);
};
