( function( blocks, element, editor, components, i18n, apiFetch ) {
    var el = element.createElement;
    var __ = i18n.__;
    var useState = element.useState;
    var useEffect = element.useEffect;

    const svgElement = el(
        'svg',
        {
            xmlns: 'http://www.w3.org/2000/svg',
            xmlnsXlink: 'http://www.w3.org/1999/xlink',
            viewBox: '-4011 -5058 256 178',
        },
        el(
            'defs',
            {},
            el(
                'style',
                {},
                '.a{clip-path:url(#b);}.b{fill:#6188ff;}.c{fill:rgba(42,43,51,0);}'
            ),
            el(
                'clipPath',
                { id: 'b' },
                el('rect', { x: '-4011', y: '-5058', width: '256', height: '178' })
            )
        ),
        el(
            'g',
            { id: 'a', className: 'a' },
            el('rect', { className: 'c', x: '-4011', y: '-5058', width: '256', height: '178' }),
            el('path', {
                className: 'b',
                d: 'M135.441,62.258,145.81,76.052l20.739-41.384c.076-.076-.076-.076,0,0l58.76,120.7c.076.076.152,0,0,0H173.462c-.076,0,.076.076,0,0L100.876,7.08C99.052,3.439,94.611.258,90.507.182h0c-4.1,0-8.545,3.257-10.369,6.9L.638,158.819c-2.128,4.1.036,10.382,3.456,13.795,2.052,2.048,3.948,3.449,6.913,3.449h79.5c4.1,0,5.089-3.257,6.913-6.9l13.826-27.589c5.321-10.618,3.456-27.589,3.456-27.589L111.246,93.3c-.076-.076.076-.076,0,0L83.594,155.371c0,.076.076,0,0,0H28.29c-.076,0-.076.076,0,0l62.217-120.7c.076-.076-.076-.076,0,0l65.673,134.5c1.824,3.716,6.265,6.9,10.369,6.9h76.043c2.889,0,4.861-1.4,6.913-3.449,3.421-3.337,5.433-9.7,3.456-13.795L176.919,7.08c-1.824-3.64-6.265-6.821-10.369-6.9-4.181,0-8.062,3.257-9.887,6.9L135.441,48.463S131.64,55.432,135.441,62.258Z',
                transform: 'translate(-4009.793 -5057.076)',
            })
        )
    );

    blocks.registerBlockType( 'mightycause/donation-form', {
        title: __( 'Mightycause Donation Form' ),
        icon: svgElement,
        category: 'widgets',
        attributes: {
            formId: {
                type: 'string',
            },
        },
        supports: {
            className: false,
            customClassName: false
        },
        edit: function( props ) {
            var [ forms, setForms ] = useState( null );
            var [ selectedForm, setSelectedForm ] = useState( null );

            useEffect( function() {
                apiFetch( { url: mightycauseBlockData.restUrl } ).then( function( forms ) {
                    setForms( forms );
                } );
            }, [] );

            useEffect( function() {
                var form = forms ? forms.find( function( form ) {
                    return form.short_token && form.short_token === props.attributes.formId;
                } ) : null;
                setSelectedForm( form );
            }, [ props.attributes.formId, forms ] );

            var options = forms ? forms.map( function( form ) {
                return {
                    label: form.label + ' (' + form.embed_type + ')',
                    value: form.short_token,
                };
            } ) : null;

            function onChangeForm( value ) {
                props.setAttributes( { formId: value } );
            }

            return [
                el( editor.InspectorControls, { key: 'inspector' },
                    el( components.PanelBody, { title: __( 'Form Settings' ) },
                        forms ? 
                        options.length == 0 ? 
                        el( 'p', null, __( 'No widgets found. Create some widgets on your Mightycause organization page to select them here!' ) ) :
                        el( components.SelectControl, {
                            label: __( 'Select Form' ),
                            value: props.attributes.formId,
                            options: options,
                            onChange: onChangeForm,
                        } ) : 
                        el( 'p', null, __( 'Your Mightycause token has not been set or is incorrect. Complete setup in plugin settings to select a form.' ) ),
                    ) 
                ),
                forms ?
                el( 'div', { className: props.className },
                    selectedForm ?
                    el( 'iframe', {
                        width: selectedForm.embed_width,
                        height: selectedForm.embed_height,
                        src: selectedForm.iframe_url,
                        scrolling: 'no',
                        marginHeight: '0',
                        marginWidth: '0',
                        frameBorder: '0'
                    } ) :
                    el( 'p', null, __( 'Select a form to preview it here.' ) )
                ) : el( 'p', null, __( 'Complete setup in plugin settings to select a form.' ) ),
            ];
        },
        save: function() {
            return null; // Rendered in PHP.
        },
    } );
} )(
    window.wp.blocks,
    window.wp.element,
    window.wp.blockEditor,
    window.wp.components,
    window.wp.i18n,
    window.wp.apiFetch
);
