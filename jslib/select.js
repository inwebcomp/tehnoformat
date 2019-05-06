HTMLCollection.prototype.select = function(options){
    let defaults = {
		transition: 'slide-fade',
        staticWidth: false,
        afterMount: false,
        afterChange: false,
        afterOpen: false,
        afterClose: false
	}; 
	
	var opts = Object.assign(defaults, options);

    elements = this;

    for (var element of elements)
    {
        let options = [],
            selected,
            opened = false,
            transition,
            _select = element,
            _component,
            _input,
            _value,
            _values;

        function mount()
        {
            init();
            construct();
            bindActions();

            if (opts.afterMount)
                opts.afterMount();
        }

        function init()
        {
            indexOptions();

            if (selected == undefined && options.length)
                selected = options[0];
        }

        function indexOptions()
        {
            let el;

            Array.from(_select.children).forEach(function(element) {
                el = {
                    value: element.value,
                    text: element.innerHTML
                };

                options.push(el);

                if (element.selected)
                    selected = el;
            }, this);
        }

        function construct()
        {
            let baseWidth = _select.offsetWidth;

            _input = _select.cloneNode(true);

            // Input
            _input.setAttribute("class", "select__input");

            // Selected value block
            _value = document.createElement('div');
            _value.setAttribute("class", "select__value");
            _value.innerText = selected.text;

            // Values
            _values = document.createElement('div');
            _values.setAttribute("class", "select__values");

            let el;
            options.forEach(function(element, index) {
                el = document.createElement("div");
                el.setAttribute("class", "select__values__option");
                el.innerText = element.text;
                el.dataset.index = index;

                if (element.value == selected.value)
                    el.classList.add("select__values__option--active");

                _values.appendChild(el);
            }, this);

            // Whole component
            _component = document.createElement('div');
            _component.setAttribute("class", "select");

            _component.appendChild(_input);
            _component.appendChild(_value);
            _component.appendChild(_values);
     
            // Replacement
            _select.replaceWith(_component);

            // Styles
            if (opts.staticWidth) {
                _value.style.width = baseWidth + 'px';
                _values.style.width = baseWidth + 'px';
            }

            // Creating transition
            // transition = new Transition(_values, opts.transition);
        }

        function bindActions()
        {
            Array.from(_values.children).forEach(function(element) {
                element.addEventListener("click", function(event) {
                    onElementClick(event, element);
                });
            });

            _input.addEventListener("change", onChange);
            
            _value.addEventListener("click", triggerValues);

            document.addEventListener('click', function(event) {
                var isClickInside = _component.contains(event.target);

                if (!isClickInside)
                    hideValues();
            });
        }

        function select(index, trigger = false) {
            selected = options[index];
            
            if (trigger)
                triggerEvent(_input, 'change');
        }

        function selectByValue(value) {
            for (let i in options) {
                if (options[i].value == value)
                    selected = options[i];
            }
        }

        function triggerEvent(element, event) {
            element.dispatchEvent(new Event(event));
        }



        function updateValuesState() {
            if (opened) {
                _value.classList.add("select__value--active");
                _values.classList.add("select__values--visible");

                // transition.enter();
            } else {
                _value.classList.remove("select__value--active");
                _values.classList.remove("select__values--visible");

                // transition.leave();
            }
        }
        
        function triggerValues() {
            opened = !opened;
            updateValuesState();
        }

        function hideValues() {
            opened = false;
            updateValuesState();

            if (opts.afterClose)
                opts.afterClose();
        }

        function showValues() {
            opened = true;
            updateValuesState();

            if (opts.afterOpen)
                opts.afterOpen();
        }

        function onChange(event) {
            _value.innerText = selected.text;
            _input.value = selected.value;

            if (opts.afterChange)
                opts.afterChange();
        }

        function onElementClick(event, element) {
            select(element.dataset.index, true);
            
            Array.from(_values.childNodes).forEach(function(el){
                el.classList.remove("select__values__option--active");
            });

            element.classList.add("select__values__option--active");

            hideValues();
        }

        mount();
    };
}