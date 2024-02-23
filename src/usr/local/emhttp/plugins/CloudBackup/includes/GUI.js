class GUI {

    constructor(test = null){

    }

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

    fieldOption( name, text, value ){
        let field = document.createElement("option");
        field.name = name;
        field.value = value;
        field.innerText = text;
        return field;
    }

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