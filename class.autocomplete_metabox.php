<?php

class AutoComplete_Metabox {
    const CONFIG = array (
        'title' => 'AutoComplete',
        'prefix' => 'autocomplete_',
        'domain' => 'autocomplete',
        'class_name' => 'AutoComplete_Metabox',
        'post-type' =>
            array (
                0 => 'post',
            ),
        'context' => 'side',
        'priority' => 'default',
        'fields' =>
            array (
                0 =>
                    array (
                        'type' => 'text',
                        'label' => 'Text',
                        'id' => 'autocomplete_text',
                    ),
                1 =>
                    array (
                        'type' => 'number',
                        'label' => 'Tokens In',
                        'step' => '1',
                        'id' => 'autocomplete_tokens-in',
                    ),
            ),
    );

    public function __construct() {
        add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );
        add_action( 'save_post', [ $this, 'save_post' ] );
    }

    public function add_meta_boxes() {
        foreach ( self::CONFIG['post-type'] as $screen ) {
            add_meta_box(
                sanitize_title( self::CONFIG['title'] ),
                self::CONFIG['title'],
                [ $this, 'add_meta_box_callback' ],
                $screen,
                self::CONFIG['context'],
                self::CONFIG['priority']
            );
        }
    }

    public function save_post( $post_id ) {
        foreach ( self::CONFIG['fields'] as $field ) {
            switch ( $field['type'] ) {
                default:
                    if ( isset( $_POST[ $field['id'] ] ) ) {
                        $sanitized = sanitize_text_field( $_POST[ $field['id'] ] );
                        update_post_meta( $post_id, $field['id'], $sanitized );
                    }
            }
        }
    }

    public function add_meta_box_callback() {
        $this->fields_div();
    }

    private function fields_div() {
        foreach ( self::CONFIG['fields'] as $field ) {
            ?><div class="components-base-control">
          <div class="components-base-control__field"><?php
              $this->label( $field );
              $this->field( $field );
              ?></div>
          </div><?php
        }
    }

    private function label( $field ) {
        switch ( $field['type'] ) {
            default:
                printf(
                    '<label class="components-base-control__label" for="%s">%s</label>',
                    $field['id'], $field['label']
                );
        }
    }

    private function field( $field ) {
        switch ( $field['type'] ) {
            case 'number':
                $this->input_minmax( $field );
                break;
            default:
                $this->input( $field );
        }
    }

    private function input( $field ) {
        printf(
            '<input class="components-text-control__input %s" id="%s" name="%s" %s type="%s" value="%s">',
            isset( $field['class'] ) ? $field['class'] : '',
            $field['id'], $field['id'],
            isset( $field['pattern'] ) ? "pattern='{$field['pattern']}'" : '',
            $field['type'],
            $this->value( $field )
        );
    }

    private function input_minmax( $field ) {
        printf(
            '<input class="components-text-control__input" id="%s" %s %s name="%s" %s type="%s" value="%s">',
            $field['id'],
            isset( $field['max'] ) ? "max='{$field['max']}'" : '',
            isset( $field['min'] ) ? "min='{$field['min']}'" : '',
            $field['id'],
            isset( $field['step'] ) ? "step='{$field['step']}'" : '',
            $field['type'],
            $this->value( $field )
        );
    }

    private function value( $field ) {
        global $post;
        if ( metadata_exists( 'post', $post->ID, $field['id'] ) ) {
            $value = get_post_meta( $post->ID, $field['id'], true );
        } else if ( isset( $field['default'] ) ) {
            $value = $field['default'];
        } else {
            return '';
        }
        return str_replace( '\u0027', "'", $value );
    }

    public static function make() {
        return new AutoComplete_Metabox();
    }
}

