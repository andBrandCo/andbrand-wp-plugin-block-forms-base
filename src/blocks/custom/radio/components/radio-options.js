import { __ } from '@wordpress/i18n';
import { PanelBody, TextControl } from '@wordpress/components';
import { select, dispatch } from '@wordpress/data';

export const RadioOptions = (props) => {
  const {
    attributes: {
      name,
    },
    actions: {
      onChangeName,
    },
    clientId
  } = props;

  // Once name is set on parent dispatch name attribute to all the children.
  const children = select('core/editor').getBlocksByClientId(clientId)[0];

  if (children) {
    children.innerBlocks.forEach(function (block) {
      dispatch('core/editor').updateBlockAttributes(block.clientId, { name: name })
    });
  }

  return (
    <PanelBody title={__('Radio Settings', 'eightshift-forms')}>

      {onChangeName &&
        <TextControl
          label={__('Name', 'eightshift-forms')}
          value={name}
          onChange={onChangeName}
        />
      }

    </PanelBody>
  );
};