// GUI class to help build the settings page for the plugin
class GUI {

    constructor(test = null){

    }

    // append the field to the settings page
    appendField( obj ){
        let field = null;

        switch ( obj['type'] ){
            case "text":
                field = this.fieldText( obj['name'], obj['placeholder'], obj['hint'], obj['value'], obj['changeFunction'], obj['blurFunction']);
                break;
            case "password":
                field = this.fieldPassword( obj['name'], obj['placeholder'], obj['hint'], obj['value'], obj['changeFunction'], obj['blurFunction']);
                break;
            case "checkbox":
                field = this.fieldCheckbox( obj['name'], obj['hint'], obj['value'], obj['changeFunction']);
                break;
            case "option":
                field = this.fieldOption( obj['name'], obj['text'], obj['value']);
                break;
            default:
                field = this.fieldCustom(obj['type'], obj['name'], obj['text'], obj['hint'], obj['value'], obj['changeFunction'], obj['blurFunction']);
                break;
        }

        document.querySelector("#providers_settings").append(field);

    }

    // create a wrapper for the field
    #fieldWrapper(){
        let container = document.createElement("div");
        container.classList.add("row");

        let subContainerLeft = document.createElement("div");
        let subContainerRight = document.createElement("div");
        subContainerLeft.classList.add("col-25");
        subContainerRight.classList.add("col-75");

        container.append(subContainerLeft);
        container.append(subContainerRight);

        return container;
    }

    // create a text field
    fieldText( name, placeholder, hint, value, changeFunction = () => {}, blurFunction = () => {}){
        let container = this.#fieldWrapper();

        let field = document.createElement("input");
        field.type = "text";
        field.title = hint;
        field.name = name;
        field.placeholder = placeholder;
        field.value = value;
        field.addEventListener("change", changeFunction );
        field.addEventListener("blur", blurFunction );

        container.children[1].appendChild(field);

        return container;       
    }

    // create a password field
    fieldPassword( name, placeholder, hint, value, changeFunction = () => {}, blurFunction = () => {}){ 
        let container = this.#fieldWrapper();

        let field = document.createElement("input");
        field.type = "password";
        field.title = hint;
        field.name = name;
        field.placeholder = placeholder;
        field.value = value;
        field.addEventListener("change", changeFunction );
        field.addEventListener("blur", blurFunction );

        container.children[1].appendChild(field);
        return container;
    }

    // create a checkbox field
    fieldCheckbox( name, hint, value, changeFunction = () => {} ){
        let container = this.#fieldWrapper();

        let field = document.createElement("input");
        field.type = "checkbox";
        field.title = hint;
        field.name = name;
        field.value = value;
        field.addEventListener("change", changeFunction );

        container.children[1].appendChild(field);
        return container;
    }

    // create an option field
    fieldOption( name, text, value ){
        let field = document.createElement("option");
        field.name = name;
        field.value = value;
        field.innerText = text;
        return field;
    }

    // create a custom field
    fieldCustom( type, name, text, hint, value, changeFunction = () => {}, blurFunction = () => {} ){
        let container = document.createElement("div");
        let field = document.createElement(type);
        field.title = hint;
        field.name = name;
        field.value = value;
        field.innerText = text;
        field.addEventListener("change", changeFunction );
        field.addEventListener("blur", blurFunction );
        
        container.append(field);
        return container;
    }

}