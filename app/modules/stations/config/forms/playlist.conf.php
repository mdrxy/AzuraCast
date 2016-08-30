<?php
return array(
    'method'        => 'post',
    'enctype'       => 'multipart/form-data',

    'elements' => array(

        'name' => array('text', array(
            'label' => 'Playlist Name',
            'required' => true,
        )),

        'weight' => array('radio', array(
            'label' => 'Playlist Weight',
            'description' => 'How often the playlist\'s songs will be played. 1 is the most infrequent, 5 is the most frequent.',
            'default' => 3,
            'required' => true,
            'class' => 'inline',
            'options' => array(
                1 => '1 - Lowest',
                2 => '2',
                3 => '3 - Default',
                4 => '4',
                5 => '5 - Highest',
            ),
        )),

        'include_in_automation' => array('radio', array(
            'label' => 'Include in Automated Assignment',
            'description' => 'If auto-assignment is enabled, use this playlist as one of the targets for songs to be redistributed into. This will overwrite the existing contents of this playlist.',
            'required' => true,
            'default' => '0',
            'options' => array(
                0 => 'No',
                1 => 'Yes',
            ),
        )),

        'submit'        => array('submit', array(
            'type'  => 'submit',
            'label' => 'Save Changes',
            'helper' => 'formButton',
            'class' => 'ui-button btn-lg btn-primary',
        )),

    ),
);