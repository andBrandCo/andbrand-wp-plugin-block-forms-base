import { __ } from '@wordpress/i18n';
import { Fragment } from '@wordpress/element';
import { PanelBody, TextControl, TabPanel, Dashicon } from '@wordpress/components';
import { FormGeneralOptions } from './form-general-options';
import { FormDynamicsCrmOptions } from './form-dynamics-crm-options';
import { FormEmailOptions } from './form-email-options';

export const FormOptions = (props) => {
  const {
    attributes: {
      blockClass,
      action,
      method,
      target,
      id,
      classes,
      type,
      dynamicsEntity,
      theme,
      successMessage,
      errorMessage,
    },
    actions: {
      onChangeAction,
      onChangeMethod,
      onChangeTarget,
      onChangeId,
      onChangeClasses,
      onChangeType,
      onChangeDynamicsEntity,
      onChangeTheme,
      onChangeSuccessMessage,
      onChangeErrorMessage,
    },
  } = props;

  const richTextClass = `${blockClass}__rich-text`;

  const formTypes = [
    { label: __('Email', 'eightshift-forms'), value: 'email' },
    { label: __('Custom', 'eightshift-forms'), value: 'custom' },
  ];

  const {
    hasThemes,
    themes = [],
    isDynamicsCrmUsed,
    dynamicsCrm = [],
  } = window.eightshiftForms;

  const themeAsOptions = hasThemes ? themes.map((tempTheme) => ({ label: tempTheme, value: tempTheme })) : [];

  let crmEntitiesAsOptions = [];
  if (isDynamicsCrmUsed) {
    crmEntitiesAsOptions = dynamicsCrm.availableEntities.map((entity) => ({ label: entity, value: entity }));
    formTypes.push({ label: __('Microsoft Dynamics CRM 365', 'eightshift-forms'), value: 'dynamics-crm' });
  }

  return (
    <PanelBody title={__('Form Settings', 'eightshift-forms')}>
      <TabPanel
        className="custom-button-tabs"
        activeClass="components-button is-button is-primary"
        tabs={[
          {
            name: 'general',
            title: <Dashicon icon="admin-generic" />,
            className: 'tab-large components-button is-button is-default custom-button-with-icon',
          },
          {
            name: 'email',
            title: <Dashicon icon="email" />,
            className: 'tab-desktop components-button is-button is-default custom-button-with-icon',
          },
          isDynamicsCrmUsed && type === 'dynamics-crm' && {
            name: 'dynamics-crm',
            title: <Dashicon icon="cloud-upload" />,
            className: 'tab-tablet components-button is-button is-default custom-button-with-icon',
          },
        ]
        }
      >
        {(tab) => (
          <Fragment>
            {tab.name === 'general' && (
              <Fragment>
                <br />
                <strong className="notice-title">{__('General Options', 'eightshift-forms')}</strong>
                <p>{__('These are general form options.', 'eightshift-forms')}</p>
                <br />
                <FormGeneralOptions
                  type={type}
                  formTypes={formTypes}
                  theme={theme}
                  themeAsOptions={themeAsOptions}
                  hasThemes={hasThemes}
                  richTextClass={richTextClass}
                  successMessage={successMessage}
                  errorMessage={errorMessage}
                  onChangeType={onChangeType}
                  onChangeTheme={onChangeTheme}
                  onChangeSuccessMessage={onChangeSuccessMessage}
                  onChangeErrorMessage={onChangeErrorMessage}
                />
              </Fragment>
            )}
            {tab.name === 'email' && (
              <Fragment>
                <br />
                <strong className="notice-title">{__('Email Options', 'eightshift-forms')}</strong>
                <p>{__('These are options for when your form is sending emails.', 'eightshift-forms')}</p>
                <br />
                <FormEmailOptions
                />
              </Fragment>
            )}
            {tab.name === 'dynamics-crm' && (
              <Fragment>
                <br />
                <strong className="notice-title">{__('Dynamics CRM Options', 'eightshift-forms')}</strong>
                <p>{__('These are options for when your form is sending data to Dynamics CRM.', 'eightshift-forms')}</p>
                <br />
                <FormDynamicsCrmOptions
                  type={type}
                  crmEntitiesAsOptions={crmEntitiesAsOptions}
                  dynamicsEntity={dynamicsEntity}
                  isDynamicsCrmUsed={isDynamicsCrmUsed}
                  onChangeDynamicsEntity={onChangeDynamicsEntity}
                />
              </Fragment>
            )}
          </Fragment>
        )}
      </TabPanel>

      {onChangeAction && type === 'custom' &&
        <TextControl
          label={__('Action', 'eightshift-forms')}
          value={action}
          onChange={onChangeAction}
        />
      }

      {onChangeMethod && type === 'custom' &&
        <TextControl
          label={__('Method', 'eightshift-forms')}
          value={method}
          onChange={onChangeMethod}
        />
      }

      {onChangeTarget && type === 'custom' &&
        <TextControl
          label={__('Target', 'eightshift-forms')}
          value={target}
          onChange={onChangeTarget}
        />
      }

      {onChangeClasses &&
        <TextControl
          label={__('Classes', 'eightshift-forms')}
          value={classes}
          onChange={onChangeClasses}
        />
      }

      {onChangeId &&
        <TextControl
          label={__('ID', 'eightshift-forms')}
          value={id}
          onChange={onChangeId}
        />
      }
    </PanelBody>
  );
};
