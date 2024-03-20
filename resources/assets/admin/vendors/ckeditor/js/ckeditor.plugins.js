window.Ckeditor = ClassicEditor.Editor

class ShortcodesCommand extends ClassicEditor.Command {
	/**
	 * Executes the command. Applies the alignment `value` to the selected blocks.
	 * If no `value` is passed, the `value` is the default one or it is equal to the currently selected block's alignment attribute,
	 * the command will remove the attribute from the selected blocks.
	 *
	 * @param {Object} [options] Options for the executed command.
	 * @param {String} [options.value] The value to apply.
	 * @fires execute
	 */
	execute( options = {} ) {
		const editor = this.editor;
		const model = editor.model;
        const selection = model.document.selection;
        const blocks = Array.from( selection.getSelectedBlocks() );
		const value = options.value;

		model.change( writer => {
            const viewFragment = editor.data.processor.toView(`[${value}][/${value}]`);
            const modelFragment = editor.data.toModel( viewFragment );                
            model.insertContent(modelFragment)
		} );
	} 
}

class Shortcodes extends ClassicEditor.Plugin {
	static get pluginName() {
		return 'Shortcodes';
	}

    constructor(editor) {
        super(editor)

        this.shortcodes = window.OCMS?.Shortcodes || []
		editor.config.define( 'shortcodes', {
			options: [ ...this.shortcodes.map( option => ( { name: option.tag } ) ) ]
		} );
    }

    init() {
        const editor = this.editor;
        const t = editor.t;
		const locale = editor.locale;
		const schema = editor.model.schema;        
        const shortcodes = editor.config.get('shortcodes.options');

		shortcodes
			.map( option => option.name )
			.forEach( option => this._addButton( option ) );        

        schema.extend( '$block', { allowAttributes: 'shortcodes' } );
		editor.model.schema.setAttributeProperties( 'shortcodes', { isFormatting: true } );        
        editor.commands.add( 'shortcodes', new ShortcodesCommand( editor ) );

        editor.ui.componentFactory.add('shortcodes', (locale) => {
            const dropdownView = ClassicEditor.createDropdown(locale);          
			const buttons = shortcodes.map( option => editor.ui.componentFactory.create(`shortcodes:${option.name}`) );
			ClassicEditor.addToolbarToDropdown( dropdownView, buttons, { enableActiveItemFocusOnDropdownOpen: true } );            

			dropdownView.buttonView.set( {
				label: t( 'Shortcodes' ),
				withText: true
			} );                   

            dropdownView.toolbarView.isVertical = true;
			// Enable button if any of the buttons is enabled.
			dropdownView.bind( 'isEnabled' ).toMany( buttons, 'isEnabled', ( ...areEnabled ) => areEnabled.some( isEnabled => isEnabled ) );   
            
			this.listenTo( dropdownView, 'execute', () => {
				editor.editing.view.focus();
			} );            

            return dropdownView;
        } );     
    }

	/**
	 * Helper method for initializing the button and linking it with an appropriate command.
	 *
	 * @private
	 * @param {String} option The name of the alignment option for which the button is added.
	 */
     _addButton( option ) {
		const editor = this.editor;
        const t      = editor.t;

		editor.ui.componentFactory.add( `shortcodes:${ option }`, locale => {
			const command = editor.commands.get( 'shortcodes' );
			const buttonView = new ClassicEditor.ButtonView( locale );

            buttonView.set( {
				label: t(option),
				withText: true
			} );

			// Bind button model to command.
			buttonView.bind( 'isEnabled' ).to( command );
			buttonView.bind( 'isOn' ).to( command, 'value', value => value === option );

			// Execute command.
			this.listenTo( buttonView, 'execute', () => {
				editor.execute( 'shortcodes', { value: option } );
				editor.editing.view.focus();
			} );

			return buttonView;
		} );
	}    
}

window.Ckeditor.builtinPlugins = window.Ckeditor.builtinPlugins.concat([Shortcodes])
window.Ckeditor.defaultConfig.toolbar.items = window.Ckeditor.defaultConfig.toolbar.items.concat(`shortcodes`)
window.Ckeditor.defaultConfig.shortcodes = {
    toolbar: (window.OCMS?.Shortcodes || []).map(opt => `shortcodes:${opt.tag}`)
}