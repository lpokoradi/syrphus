SET @TextTypeId = (SELECT id FROM `field_types` WHERE `name` LIKE 'TextType');

INSERT INTO `field_type_options` (`field_type_id`, `name`, `type`, `defaultValue`, `description`)
    VALUES ( @TextTypeId, 'data', 'mixed',
    'Defaults to field of the underlying object (if there is one)',
    'When you create a form, each field initially displays the value of the corresponding property of the form\`s domain object (if an object is bound to the form). If you want to override the initial value for the form or just an individual field, you can set it in the data option:');

INSERT INTO `field_type_options` (`field_type_id`, `name`, `type`, `defaultValue`, `description`)
    VALUES ( @TextTypeId, 'disabled', 'boolean',
    'false',
    'If you don\`t want a user to modify the value of a field, you can set the disabled option to true. Any submitted value will be ignored.');

INSERT INTO `field_type_options` (`field_type_id`, `name`, `type`, `defaultValue`, `description`)
    VALUES ( @TextTypeId, 'empty_data', 'mixed',
    'The default value is \`\` (the empty string).',
    'This option determines what value the field will return when the submitted value is empty.');

INSERT INTO `field_type_options` (`field_type_id`, `name`, `type`, `defaultValue`, `description`)
    VALUES ( @TextTypeId, 'error_bubbling', 'boolean',
    'false unless the form is compound',
    'If true, any errors for this field will be passed to the parent field or form. For example, if set to true on a normal field, any errors for that field will be attached to the main form, not to the specific field.');

INSERT INTO `field_type_options` (`field_type_id`, `name`, `type`, `defaultValue`, `description`)
    VALUES ( @TextTypeId, 'error_mapping', 'array',
    'array()',
    '');

INSERT INTO `field_type_options` (`field_type_id`, `name`, `type`, `defaultValue`, `description`)
    VALUES ( @TextTypeId, 'label', 'string',
    'The label is "guessed" from the field name',
    'Sets the label that will be used when rendering the field. Setting to false will suppress the label. The label can also be directly set inside the template:');

INSERT INTO `field_type_options` (`field_type_id`, `name`, `type`, `defaultValue`, `description`)
    VALUES ( @TextTypeId, 'label_attr', 'array',
    'array()',
    'Sets the HTML attributes for the <label> element, which will be used when rendering the label for the field. It\`s an associative array with HTML attribute as a key. This attributes can also be directly set inside the template:');


