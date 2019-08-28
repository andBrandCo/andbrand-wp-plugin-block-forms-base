import { Fragment } from '@wordpress/element';
import { InspectorControls } from '@wordpress/editor';

import { getActions } from 'EighshiftBlocksGetActions';
import manifest from './manifest.json';

import { LabelOptions } from '../../components/label/components/label-options';
import { SelectOptions } from './components/select-options';
import { SelectEditor } from './components/select-editor';

export const Select = (props) => {
  const {
    attributes,
    attributes: {
      label,
    },
    clientId,
  } = props;

  const actions = getActions(props, manifest);

  return (
    <Fragment>
      <InspectorControls>
        <LabelOptions
          label={label}
          onChangeLabel={actions.onChangeLabel}
        />
        <SelectOptions
          attributes={attributes}
          actions={actions}
          clientId={clientId}
        />
      </InspectorControls>
      <SelectEditor
        attributes={attributes}
        actions={actions}
      />
    </Fragment>
  );
};