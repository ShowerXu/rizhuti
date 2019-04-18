<?php if ( ! defined( 'ABSPATH' )  ) { die; } // Cannot access directly.

//
// Set a unique slug-like ID
//
$prefix = 'csf_demo_customizer';

//
// Create customize options
//
CSF::createCustomizeOptions( $prefix );

//
// Create a section
//
CSF::createSection( $prefix, array(
  'title'    => 'CSF - Overview',
  'priority' => 1,
  'fields'   => array(

    //
    // A text field
    //
    array(
      'id'    => 'opt-text',
      'type'  => 'text',
      'title' => 'Text',
    ),

    array(
      'id'    => 'opt-textarea',
      'type'  => 'textarea',
      'title' => 'Textarea',
      'help'  => 'The help text of the field.',
    ),

    array(
      'id'    => 'opt-upload',
      'type'  => 'upload',
      'title' => 'Upload',
    ),

    array(
      'id'    => 'opt-switcher',
      'type'  => 'switcher',
      'title' => 'Switcher',
      'label' => 'The label text of the switcher.',
    ),

    array(
      'id'      => 'opt-color',
      'type'    => 'color',
      'title'   => 'Color',
      'default' => '#3498db',
    ),

    array(
      'id'    => 'opt-checkbox',
      'type'  => 'checkbox',
      'title' => 'Checkbox',
      'label' => 'The label text of the checkbox.',
    ),

    array(
      'id'      => 'opt-radio',
      'type'    => 'radio',
      'title'   => 'Radio',
      'options' => array(
        'yes'   => 'Yes, Please.',
        'no'    => 'No, Thank you.',
      ),
      'default' => 'yes',
    ),

    array(
      'id'          => 'opt-select',
      'type'        => 'select',
      'title'       => 'Select',
      'placeholder' => 'Select an option',
      'options'     => array(
        'opt-1'     => 'Option 1',
        'opt-2'     => 'Option 2',
        'opt-3'     => 'Option 3',
      ),
    ),

  )
) );

//
// Create a section
//
CSF::createSection( $prefix, array(
  'id'       => 'nested_panel',
  'title'    => 'CSF - Nested Panels',
  'priority' => 2,
) );

//
// Create a section
//
CSF::createSection( $prefix, array(
  'parent'   => 'nested_panel',
  'title'    => 'Nested Panel 1',
  'priority' => 3,
  'fields'   => array(

    array(
      'id'    => 'opt-text-1',
      'type'  => 'text',
      'title' => 'Text',
    ),

    array(
      'id'    => 'opt-textarea-1',
      'type'  => 'textarea',
      'title' => 'Textarea',
    ),

  ),
) );

//
// Create a section
//
CSF::createSection( $prefix, array(
  'parent'   => 'nested_panel',
  'title'    => 'Nested Panel 2',
  'priority' => 4,
  'fields'   => array(

    array(
      'id'    => 'opt-color-1',
      'type'  => 'color',
      'title' => 'Color 1',
    ),

    array(
      'id'    => 'opt-color-2',
      'type'  => 'color',
      'title' => 'Color 2',
    ),

    array(
      'id'    => 'opt-color-3',
      'type'  => 'color',
      'title' => 'Color 3',
    ),

  ),
) );
