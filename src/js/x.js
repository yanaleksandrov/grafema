/* X.js MIT license */
(function() {
    "use strict";
    var __webpack_exports__ = {};
    function setClasses(el, value) {
        if (Array.isArray(value)) {
            value = value.join(" ");
        } else if (typeof value === "function") {
            value = value();
        } else if (typeof value === "object" && value !== null) {
            return setClassesFromObject(el, value);
        }
        return setClassesFromString(el, value);
    }
    function setClassesFromString(el, classString) {
        let missingClasses = classString => classString.split(" ").filter((i => !el.classList.contains(i))).filter(Boolean);
        let addClassesAndReturnUndo = classes => {
            el.classList.add(...classes);
            return () => el.classList.remove(...classes);
        };
        classString = classString === true ? "" : classString || "";
        return addClassesAndReturnUndo(missingClasses(classString));
    }
    function setClassesFromObject(el, classObject) {
        let classes = Object.entries(classObject), split = classString => classString.split(" ").filter(Boolean);
        let forAdd = classes.flatMap((([classString, bool]) => bool ? split(classString) : false)).filter(Boolean);
        let forRemove = classes.flatMap((([classString, bool]) => !bool ? split(classString) : false)).filter(Boolean);
        const added = forAdd.filter((i => !el.classList.contains(i) && (el.classList.add(i), 
        true)));
        const removed = forRemove.filter((i => el.classList.contains(i) && (el.classList.remove(i), 
        true)));
        return () => {
            removed.forEach((i => el.classList.add(i)));
            added.forEach((i => el.classList.remove(i)));
        };
    }
    function setStyles(el, value) {
        if (typeof value === "object" && value !== null) {
            return setStylesFromObject(el, value);
        }
        return ((el, value) => {
            let cache = el.getAttribute("style", value);
            el.setAttribute("style", value);
            return () => {
                el.setAttribute("style", cache || "");
            };
        })(el, value);
    }
    function setStylesFromObject(el, value) {
        let previousStyles = {};
        Object.entries(value).forEach((([key, value]) => {
            previousStyles[key] = el.style[key];
            if (!key.startsWith("--")) {
                key = key.replace(/([a-z])([A-Z])/g, "$1-$2").toLowerCase();
            }
            el.style.setProperty(key, value);
        }));
        setTimeout((() => el.style.length === 0 && el.removeAttribute("style")));
        return () => setStyles(el, previousStyles);
    }
    function debounce(func, wait, immediate) {
        let timeout;
        return function() {
            let context = this, args = arguments;
            let later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            let callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    }
    function saferEval(expression, dataContext, additionalHelperVariables = {}, noReturn = false) {
        expression = noReturn ? `with($data){${expression}}` : isKebabCase(expression) ? `var result;with($data){result=$data['${expression}']};return result` : `var result;with($data){result=${expression}};return result`;
        return new Function([ "$data", ...Object.keys(additionalHelperVariables) ], expression)(dataContext, ...Object.values(additionalHelperVariables));
    }
    function getAttributes(el) {
        const regexp = /^(x-|x.|@|:)/;
        return [ ...el.attributes ].filter((({name: name}) => regexp.test(name))).map((({name: name, value: value}) => {
            const startsWith = name.match(regexp)[0];
            const root = name.replace(startsWith, "");
            const parts = root.split(".");
            return {
                name: name,
                directive: startsWith === "x-" ? name : startsWith === ":" ? "x-bind" : "",
                event: startsWith === "@" ? parts[0] : "",
                expression: value,
                prop: startsWith === "x." ? parts[0] : "",
                modifiers: startsWith === "x." ? parts.slice(1) : root.split(".").slice(1)
            };
        }));
    }
    function updateAttribute(el, name, value) {
        if (name === "value") {
            if (el.type === "radio") {
                el.checked = el.value === value;
            } else if (el.type === "checkbox") {
                el.checked = Array.isArray(value) ? value.some((val => val === el.value)) : !!value;
            } else if (el.tagName === "SELECT") {
                updateSelect(el, value);
            } else {
                el.value = value;
            }
        } else if (name === "class") {
            bindClasses(el, value);
        } else if (name === "style") {
            bindStyles(el, value);
        } else if ([ "disabled", "readonly", "required", "checked", "autofocus", "autoplay", "hidden" ].includes(name)) {
            !!value ? el.setAttribute(name, "") : el.removeAttribute(name);
        } else {
            el.setAttribute(name, value);
        }
    }
    function bindClasses(el, value) {
        if (el._x_undoAddedClasses) {
            el._x_undoAddedClasses();
        }
        el._x_undoAddedClasses = setClasses(el, value);
    }
    function bindStyles(el, value) {
        if (el._x_undoAddedStyles) {
            el._x_undoAddedStyles();
        }
        el._x_undoAddedStyles = setStyles(el, value);
    }
    function updateSelect(el, value) {
        const arrayWrappedValue = [].concat(value).map((value => value + ""));
        Array.from(el.options).forEach((option => {
            option.selected = arrayWrappedValue.includes(option.value || option.text);
        }));
    }
    function eventCreate(eventName, detail = {}) {
        return new CustomEvent(eventName, {
            detail: detail,
            bubbles: true,
            cancelable: true
        });
    }
    function getNextModifier(modifiers, modifier, defaultValue = "") {
        return modifiers[modifiers.indexOf(modifier) + 1] || defaultValue;
    }
    function isInputField(el) {
        return [ "input", "select", "textarea" ].includes(el.tagName.toLowerCase());
    }
    function isKebabCase(str) {
        return /^[a-z][a-z\d]*(-[a-z\d]+)+$/.test(str);
    }
    function isEmpty(variable) {
        return variable === "" || variable === null || Array.isArray(variable) && variable.length === 0 || typeof variable === "object" && Object.keys(variable).length === 0;
    }
    function domReady() {
        return new Promise((resolve => {
            if (document.readyState === "loading") {
                document.addEventListener("DOMContentLoaded", resolve);
            } else {
                resolve();
            }
        }));
    }
    function domWalk(el, callback) {
        callback(el);
        let node = el.firstElementChild;
        while (node) {
            if (node.hasAttribute("x-data")) return;
            domWalk(node, callback);
            node = node.nextElementSibling;
        }
    }
    function fetchProps(rootElement, data) {
        const fetched = [];
        domWalk(rootElement, (el => {
            getAttributes(el).forEach((attribute => {
                let {modifiers: modifiers, prop: prop, name: name} = attribute;
                if (prop) {
                    if (el.type === "checkbox" && data[prop] === undefined) {
                        data[prop] = rootElement.querySelectorAll(`[${CSS.escape(name)}]`).length > 1 ? [] : "";
                    }
                    if (isInputField(el)) {
                        let modelExpression = generateExpressionForProp(el, data, prop, modifiers);
                        let oldValue = data[prop] !== undefined ? data[prop] : null, newValue = saferEval(modelExpression, data, {
                            $el: el
                        });
                        data[prop] = oldValue && isEmpty(newValue) ? oldValue : newValue;
                    }
                    fetched.push({
                        el: el,
                        attribute: attribute
                    });
                }
            }));
        }));
        document.dispatchEvent(eventCreate("x:fetched", {
            data: data,
            fetched: fetched
        }));
        return data;
    }
    function generateExpressionForProp(el, data, prop, modifiers) {
        let rightSideOfExpression, tag = el.tagName.toLowerCase();
        if (el.type === "checkbox") {
            if (Array.isArray(data[prop])) {
                rightSideOfExpression = `$el.checked ? ${prop}.concat([$el.value]) : [...${prop}.splice(0, ${prop}.indexOf($el.value)), ...${prop}.splice(${prop}.indexOf($el.value)+1)]`;
            } else {
                rightSideOfExpression = `$el.checked`;
            }
        } else if (el.type === "radio") {
            rightSideOfExpression = `$el.checked ? $el.value : (typeof ${prop} !== 'undefined' ? ${prop} : '')`;
        } else if (tag === "select" && el.multiple) {
            rightSideOfExpression = `Array.from($el.selectedOptions).map(option => ${modifiers.includes("number") ? "parseFloat(option.value || option.text)" : "option.value || option.text"})`;
        } else {
            rightSideOfExpression = modifiers.includes("number") ? "parseFloat($el.value)" : modifiers.includes("trim") ? "$el.value.trim()" : "$el.value";
        }
        if (!el.hasAttribute("name")) {
            el.setAttribute("name", prop);
        }
        return `$data['${prop}'] = ${rightSideOfExpression}`;
    }
    class Component {
        constructor(el) {
            this.el = el;
            this.rawData = saferEval(el.getAttribute("x-data") || "{}", {});
            this.rawData = fetchProps(el, this.rawData);
            this.data = this.wrapDataInObservable(this.rawData);
            this.initialize(el, this.data);
        }
        evaluate(expression, additionalHelperVariables) {
            let affectedDataKeys = [];
            const proxiedData = new Proxy(this.data, {
                get(object, prop) {
                    affectedDataKeys.push(prop);
                    return object[prop];
                }
            });
            const result = saferEval(expression, proxiedData, additionalHelperVariables);
            return {
                output: result,
                deps: affectedDataKeys
            };
        }
        wrapDataInObservable(data) {
            let self = this;
            self.concernedData = [];
            return new Proxy(data, {
                set(obj, property, value) {
                    const setWasSuccessful = Reflect.set(obj, property, value);
                    if (self.concernedData.indexOf(property) === -1) {
                        self.concernedData.push(property);
                    }
                    self.refresh();
                    return setWasSuccessful;
                }
            });
        }
        initialize(rootElement, data, additionalHelperVariables) {
            const self = this;
            domWalk(rootElement, (el => {
                getAttributes(el).forEach((attribute => {
                    let {directive: directive, event: event, expression: expression, modifiers: modifiers, prop: prop} = attribute;
                    if (event) {
                        self.registerListener(el, event, modifiers, expression);
                    }
                    if (prop) {
                        let event = [ "select-multiple", "select", "checkbox", "radio" ].includes(el.type) || modifiers.includes("lazy") ? "change" : "input";
                        self.registerListener(el, event, modifiers, generateExpressionForProp(el, data, prop, modifiers));
                        let {output: output} = self.evaluate(prop, additionalHelperVariables);
                        updateAttribute(el, "value", output);
                    }
                    if (directive in x.directives) {
                        let output = expression;
                        if (directive !== "x-for") {
                            try {
                                ({output: output} = self.evaluate(expression, additionalHelperVariables));
                            } catch (error) {}
                        }
                        x.directives[directive](el, output, attribute, x, self);
                    }
                }));
            }));
        }
        refresh() {
            const self = this;
            debounce((() => {
                domWalk(self.el, (el => {
                    getAttributes(el).forEach((attribute => {
                        let {directive: directive, expression: expression, prop: prop} = attribute;
                        if (prop) {
                            let {output: output, deps: deps} = self.evaluate(prop);
                            if (self.concernedData.filter((i => deps.includes(i))).length > 0) {
                                updateAttribute(el, "value", output);
                                document.dispatchEvent(eventCreate("x:refreshed", {
                                    attribute: attribute,
                                    output: output
                                }));
                            }
                        }
                        if (directive in x.directives) {
                            let output = expression, deps = [];
                            if (directive !== "x-for") {
                                try {
                                    ({output: output, deps: deps} = self.evaluate(expression));
                                } catch (error) {}
                            } else {
                                [, deps] = expression.split(" in ");
                            }
                            if (self.concernedData.filter((i => deps.includes(i))).length > 0) {
                                x.directives[directive](el, output, attribute, x, self);
                            }
                        }
                    }));
                }));
                self.concernedData = [];
            }), 0)();
        }
        registerListener(el, event, modifiers, expression) {
            const self = this;
            const observers = [];
            function removeIntersectionObserver(element) {
                const index = observers.findIndex((item => item.el === element));
                if (index >= 0) {
                    const {observer: observer} = observers[index];
                    observer.unobserve(element);
                    observers.splice(index, 1);
                }
            }
            let target = el;
            if (modifiers.includes("window")) target = window;
            if (modifiers.includes("document")) target = document;
            if (modifiers.includes("outside")) target = document;
            function eventHandler(e) {
                if (modifiers.includes("prevent")) {
                    e.preventDefault();
                }
                if (modifiers.includes("stop")) {
                    e.stopPropagation();
                }
                let wait = 0;
                if (modifiers.includes("delay")) {
                    const numericValue = getNextModifier(modifiers, "delay").split("ms")[0];
                    wait = !isNaN(numericValue) ? Number(numericValue) : 250;
                }
                debounce((() => {
                    self.runListenerHandler(expression, e);
                    if (modifiers.includes("once")) {
                        target.removeEventListener(event, eventHandler);
                        if (e instanceof IntersectionObserverEntry) {
                            removeIntersectionObserver(e.target);
                        }
                    }
                }), wait)();
            }
            if (modifiers.includes("outside")) {
                target.addEventListener(event, (e => {
                    if (el.contains(e.target)) return;
                    if (el.offsetWidth < 1 && el.offsetHeight < 1) return;
                    this.runListenerHandler(expression, e);
                }));
            } else {
                if (event === "load") {
                    eventHandler(eventCreate("load", {}));
                } else if (event === "intersect") {
                    const observer = new IntersectionObserver((entries => entries.forEach((entry => entry.isIntersecting && eventHandler(entry)))));
                    observer.observe(el);
                    observers.push({
                        el: el,
                        observer: observer
                    });
                } else {
                    target.addEventListener(event, eventHandler);
                }
            }
        }
        runListenerHandler(expression, e) {
            const methods = {};
            Object.keys(x.methods).forEach((key => methods[key] = x.methods[key](e, e.target)));
            let data = {}, el = e.target;
            while (el && !(data = el.__x_for_data)) {
                el = el.parentElement;
            }
            saferEval(expression, this.data, {
                ...{
                    $el: e.target,
                    $event: e,
                    $refs: this.getRefsProxy()
                },
                ...data,
                ...methods
            }, true);
        }
        getRefsProxy() {
            let self = this;
            return new Proxy({}, {
                get(object, property) {
                    let ref;
                    domWalk(self.el, (el => {
                        if (el.hasAttribute("x-ref") && el.getAttribute("x-ref") === property) {
                            ref = el;
                        }
                    }));
                    return ref;
                }
            });
        }
    }
    const scripts_x = {
        directives: {},
        methods: {},
        start: async function() {
            await domReady();
            this.discoverComponents((el => this.initializeElement(el)));
            this.listenUninitializedComponentsAtRunTime((el => this.initializeElement(el)));
        },
        discoverComponents: callback => {
            Array.from(document.querySelectorAll("[x-data]")).forEach(callback);
        },
        listenUninitializedComponentsAtRunTime: callback => {
            let observer = new MutationObserver((mutations => mutations.forEach((mutation => Array.from(mutation.addedNodes).filter((node => node.nodeType === 1 && node.matches("[x-data]"))).forEach(callback)))));
            observer.observe(document.querySelector("body"), {
                childList: true,
                attributes: true,
                subtree: true
            });
        },
        initializeElement: el => {
            el.__x = new Component(el);
        }
    };
    const storage = {
        get: (name, type) => {
            if (!name) return;
            if (type === "cookie") {
                let matches = document.cookie.match(new RegExp("(?:^|; )" + name.replace(/([.$?*|{}()\[\]\\\/+^])/g, "\\$1") + "=([^;]*)"));
                if (matches) {
                    let res = decodeURIComponent(matches[1]);
                    try {
                        return JSON.parse(res);
                    } catch (e) {
                        return res;
                    }
                }
            }
            if (type === "local") {
                return localStorage.getItem(name);
            }
        },
        set: (name, value, type, options = {
            path: "/"
        }) => {
            if (!name) return;
            if (value instanceof Object) {
                value = JSON.stringify(value);
            }
            if (type === "cookie") {
                options = options || {};
                if (options.expires instanceof Date) {
                    options.expires = options.expires.toUTCString();
                }
                let updatedCookie = encodeURIComponent(name) + "=" + encodeURIComponent(value);
                for (let optionKey in options) {
                    updatedCookie += "; " + optionKey;
                    let optionValue = options[optionKey];
                    if (optionValue !== true) {
                        updatedCookie += "=" + optionValue;
                    }
                }
                document.cookie = updatedCookie;
            }
            if (type === "local") {
                if (value) {
                    localStorage.setItem(name, value);
                } else {
                    localStorage.removeItem(name);
                }
            }
        }
    };
    function computeExpires(str) {
        let lastCh = str.charAt(str.length - 1), value = parseInt(str, 10);
        const methods = {
            y: "FullYear",
            m: "Month",
            d: "Date",
            h: "Hours",
            i: "Minutes",
            s: "Seconds"
        };
        if (lastCh in methods) {
            const date = new Date;
            const method = methods[lastCh];
            date[`set${method}`](date[`get${method}`]() + value);
            return date;
        }
        return null;
    }
    function isStorageModifier(modifiers) {
        return [ "cookie", "local" ].some((modifier => modifiers.includes(modifier)));
    }
    function getStorageType(modifiers) {
        return modifiers.includes("cookie") ? "cookie" : "local";
    }
    function castToType(a, value) {
        const type = typeof a;
        switch (type) {
          case "string":
            return String(value);

          case "number":
            return Number.isInteger(a) ? parseInt(value, 10) : parseFloat(value);

          case "boolean":
            return Boolean(value);

          case "object":
            if (a instanceof Date) {
                return new Date(value);
            } else if (Array.isArray(a)) {
                return Array.from(value);
            } else {
                return Object(value);
            }

          case "undefined":
            if (a === null) {
                return null;
            }
            return value === "true" ? true : value === "false" ? false : value;

          default:
            return value;
        }
    }
    document.addEventListener("x:refreshed", (({detail: detail}) => {
        const {modifiers: modifiers, prop: prop} = detail.attribute;
        if (isStorageModifier(modifiers)) {
            const type = getStorageType(modifiers);
            const expire = getNextModifier(modifiers, type);
            if (detail.output) {
                storage.set(prop, detail.output, type, {
                    expires: computeExpires(expire),
                    secure: true
                });
            } else {
                storage.set(prop, null, type, {
                    expires: new Date,
                    path: "/"
                });
            }
        }
    }));
    document.addEventListener("x:fetched", (({detail: detail}) => {
        const {data: data, fetched: fetched} = detail;
        fetched.forEach((item => {
            const {attribute: {modifiers: modifiers, prop: prop}} = item;
            if (isStorageModifier(modifiers)) {
                const type = getStorageType(modifiers);
                const value = storage.get(prop, type);
                data[prop] = castToType(data[prop], value || data[prop]);
            }
        }));
    }));
    const prefix = "x-";
    function directive(name, callback) {
        name = `${prefix}${name}`;
        if (!scripts_x.directives[name]) {
            scripts_x.directives[name] = callback;
        } else {
            console.warn(`X.js: directive '${name}' is already exists.`);
        }
    }
    directive("for", ((el, expression, attribute, x, component) => {
        if (typeof expression !== "string") {
            return;
        }
        const regex = /^\(?(\w+)(?:,\s*(\w+))?\)?\s+in\s+(\w+)$/;
        const [, item, index = "key", items] = expression.match(regex) || [];
        const dataItems = saferEval(`${items}`, component.data);
        while (el.nextSibling) {
            el.nextSibling.remove();
        }
        dataItems.forEach(((dataItem, key) => {
            const clone = el.cloneNode(true);
            clone.removeAttribute("x-for");
            (async () => {
                clone.__x_for_data = {
                    [item]: dataItem,
                    [index]: key
                };
                await component.initialize(clone, component.data, clone.__x_for_data);
                el.parentNode.appendChild(clone);
            })();
        }));
    }));
    directive("bind", ((el, expression, {name: name}, x, component) => {
        if (name === ":attributes" && typeof expression === "object") {
            Object.entries(expression).forEach((([key, value]) => updateAttribute(el, key, value)));
        } else {
            updateAttribute(el, name.replace(":", ""), expression);
        }
    }));
    directive("html", ((el, expression, attribute, x, component) => {
        el.innerHTML = expression;
    }));
    directive("text", ((el, expression, attribute, x, component) => {
        el.innerText = expression;
    }));
    directive("show", ((el, expression, attribute, x, component) => {
        el.style.display = expression ? "block" : "none";
    }));
    const methods_prefix = "$";
    function method(name, callback) {
        name = `${methods_prefix}${name}`;
        if (!scripts_x.methods[name]) {
            scripts_x.methods[name] = callback;
        } else {
            console.warn(`X.js: method '${name}' is already exists.`);
        }
    }
    const BYTES_IN_MB = 1048576;
    method("fetch", ((e, el) => (url, options = {}, callback) => {
        let tagName = el.tagName.toLowerCase(), method = tagName === "form" ? "post" : "get", data = tagName === "form" ? new FormData(el) : new FormData, xhr = new XMLHttpRequest;
        switch (tagName) {
          case "form":
            Array.from(el.querySelectorAll("input[type='file']")).forEach((input => {
                input.files && [ ...input.files ].forEach((file => data.append(input.name, file)));
            }));
            break;

          case "textarea":
          case "select":
          case "input":
            if (el.type === "file" && el.files) {
                Array.from(el.files).forEach((file => data.append(el.name, file)));
            } else {
                el.name && data.append(el.name, el.value);
            }
            break;
        }
        return new Promise((resolve => {
            xhr.open(method, url);
            for (const i in options.headers) {
                xhr.setRequestHeader(i, options.headers[i]);
            }
            xhr.withCredentials = options.credentials === "include";
            xhr.onloadstart = xhr.upload.onprogress = event => callback?.(onProgress(event, xhr));
            xhr.onloadend = event => resolve((() => callback?.(onProgress(event, xhr))));
            xhr.send(data);
        })).then((response => response()));
    }));
    function onProgress(event, xhr) {
        const {loaded: loaded = 0, total: total = 0, type: type} = event;
        const {response: response = "", responseText: responseText = "", status: status = "", responseURL: responseURL = ""} = xhr;
        return {
            blob: new Blob([ response ]),
            json: JSON.parse(responseText || "[]"),
            raw: response,
            status: status,
            url: responseURL,
            loaded: convertTo(loaded),
            total: convertTo(total),
            percent: total > 0 ? Math.round(loaded / total * 100) : 0,
            start: type === "loadstart",
            progress: type === "progress",
            end: type === "loadend"
        };
    }
    function convertTo(number) {
        return Math.round(number / BYTES_IN_MB * 100) / 100;
    }
    method("dispatch", ((e, el) => (name, detail = {}) => {
        el.dispatchEvent(new CustomEvent(name, {
            detail: detail,
            bubbles: true,
            composed: true,
            cancelable: true
        }));
    }));
    directive("sticky", ((el, expression, attribute, x, component) => {
        let style = el.parentElement.currentStyle || window.getComputedStyle(el.parentElement);
        if (style.position !== "relative") {
            return false;
        }
        let rect = el.getBoundingClientRect();
        let diff = rect.height - document.scrollingElement.offsetHeight;
        let paddingTop = parseInt(style.paddingTop) + 42;
        let paddingBottom = parseInt(style.paddingBottom);
        let lastScroll = 0;
        let bottomPoint = 0;
        let value = "top: " + paddingTop + "px";
        function calcPosition() {
            if (diff > 0) {
                let y = document.scrollingElement.scrollTop;
                if (window.scrollY > lastScroll) {
                    if (y > diff) {
                        bottomPoint = diff * -1 - paddingBottom;
                        value = "top: " + bottomPoint + "px";
                    } else {
                        value = "top: " + (y * -1 - paddingBottom) + "px";
                    }
                } else {
                    bottomPoint = bottomPoint + (lastScroll - window.scrollY);
                    if (bottomPoint < paddingTop) {
                        value = "top: " + bottomPoint + "px";
                    }
                }
            }
            el.setAttribute("style", "position: sticky;" + value);
            lastScroll = window.scrollY;
        }
        [ "load", "scroll", "resize" ].forEach((event => window.addEventListener(event, (() => calcPosition()))));
    }));
    directive("autocomplete", ((el, expression, attribute, x, component) => {
        el.setAttribute("readonly", true);
        el.onfocus = () => setTimeout((() => el.removeAttribute("readonly")), 10);
        el.onblur = () => el.setAttribute("readonly", true);
    }));
    directive("highlight", ((el, expression, {modifiers: modifiers}, x, component) => {
        let lang = modifiers[0] || "html", wrapper = document.createElement("code");
        wrapper.classList.add("language-" + lang);
        wrapper.innerHTML = el.innerHTML;
        el.classList.add("line-numbers");
        el.innerHTML = "";
        el.setAttribute("data-lang", lang.toUpperCase());
        el.appendChild(wrapper);
    }));
    directive("collapse", ((el, expression, attribute, x, component) => {}));
    directive("anchor", ((el, expression, attribute, x, component) => {
        let hash = window.location.hash.replace("#", ""), anchor = el.innerText.toLowerCase().replaceAll(" ", "-");
        if (hash && hash === anchor) {
            el.scrollIntoView({
                behavior: "smooth"
            });
        }
        el.addEventListener("click", (e => {
            e.preventDefault();
            window.location.hash = anchor;
            el.scrollIntoView({
                behavior: "smooth"
            });
        }), false);
        const observer = new IntersectionObserver((entries => {
            entries.forEach((entry => {
                if (!entry.isIntersecting || entry.intersectionRatio !== 1) {
                    return;
                }
                window.location.hash = anchor;
            }));
        }), {
            threshold: 1
        });
        observer.observe(el);
    }));
    directive("listen", ((el, expression, attribute, x, component) => {
        if (!expression) {
            return false;
        }
        let name = "listen-node";
        function _play(aud, icn) {
            icn.classList.add("playing");
            aud.play();
            aud.setAttribute("data-playing", "true");
            aud.addEventListener("ended", (function() {
                _pause(aud, icn);
                aud.parentNode.style.background = null;
                return false;
            }));
        }
        function _pause(aud, icn) {
            aud.pause();
            aud.setAttribute("data-playing", "false");
            icn.classList.remove("playing");
        }
        let aud, icn;
        let css = document.createElement("style");
        css.type = "text/css";
        css.innerHTML = ".listen-node {display: inline-block; background:rgba(0, 0, 0, 0.05); padding: 1px 8px 2px; border-radius:3px; cursor: pointer;} .listen-node i {font-size: 0.65em; border: 0.5em solid transparent; border-left: 0.75em solid; display: inline-block; margin-right: 2px;margin-bottom: 1px;} .listen-node .playing { border: 0; border-left: 0.75em double; border-right: 0.5em solid transparent; height: 1em;}";
        document.getElementsByTagName("head")[0].appendChild(css);
        aud = document.createElement("audio");
        icn = document.createElement("i");
        aud.src = el.getAttribute("data-src");
        aud.setAttribute("data-playing", "false");
        el.id = name + "-" + i;
        el.insertBefore(icn, el.firstChild);
        el.appendChild(aud);
        document.addEventListener("click", (e => {
            let aud, elm, icn;
            if (e.target.className === name) {
                aud = e.target.children[1];
                elm = e.target;
                icn = e.target.children[0];
            } else if (e.target.parentElement && e.target.parentElement.className === name) {
                aud = e.target.parentElement.children[1];
                elm = e.target.parentElement;
                icn = e.target;
            }
            if (aud && elm && icn) {
                aud.srt = parseInt(elm.getAttribute("data-start")) || 0;
                aud.end = parseInt(elm.getAttribute("data-end")) || aud.duration;
                if (aud && aud.getAttribute("data-playing") === "false") {
                    if (aud.srt > aud.currentTime || aud.end < aud.currentTime) {
                        aud.currentTime = aud.srt;
                    }
                    _play(aud, icn);
                } else {
                    _pause(aud, icn);
                }
                (function loop() {
                    let d = requestAnimationFrame(loop);
                    let percent = (aud.currentTime - aud.srt) * 100 / (aud.end - aud.srt);
                    percent = percent < 100 ? percent : 100;
                    elm.style.background = "linear-gradient(to right, rgba(0, 0, 0, 0.1)" + percent + "%, rgba(0, 0, 0, 0.05)" + percent + "%)";
                    if (aud.end < aud.currentTime) {
                        _pause(aud, icn);
                        cancelAnimationFrame(d);
                    }
                })();
            }
        }));
    }));
    directive("textarea", ((el, expression, attribute, x, component) => {
        if ("TEXTAREA" !== el.tagName.toUpperCase()) {
            return false;
        }
        el.addEventListener("input", (() => {
            let max = parseInt(expression) || 99, rows = parseInt(el.value.split(/\r|\r\n|\n/).length);
            if (rows > max) {
                return false;
            }
            let styles = getComputedStyle(el, null), border = parseInt(styles.getPropertyValue("border-width")) * 4;
            el.style.height = "auto";
            el.style.height = el.scrollHeight + border + 4 + "px";
        }), false);
    }));
    directive("tooltip", ((el, expression, {modifiers: modifiers}, x, component) => {
        let position, trigger;
        if (modifiers) {
            modifiers.forEach((modifier => {
                position = [ "top", "right", "bottom", "left" ].includes(modifier) ? modifier : "top";
                trigger = [ "hover", "click" ].includes(modifier) ? modifier : "hover";
            }));
        }
        if (position && trigger) {
            try {
                new Drooltip({
                    element: el,
                    trigger: trigger,
                    position: position,
                    background: "#fff",
                    color: "var(--grafema-dark)",
                    animation: "bounce",
                    content: content || null,
                    callback: null
                });
            } catch (e) {
                console.warn("You forgot to connect the library Drooltip.js");
            }
        }
    }));
    directive("progress", ((el, expression, {modifiers: modifiers}, x, component) => {
        new IntersectionObserver(((entries, observer) => {
            entries.forEach((entry => {
                if (entry.isIntersecting) {
                    let [value = 100, from = 0, to = 100, duration = "0ms"] = modifiers;
                    let start = parseInt(from) / parseInt(value) * 100;
                    let end = parseInt(to) / parseInt(value) * 100;
                    if (start > end) {
                        [end, start] = [ start, end ];
                    }
                    el.style.setProperty("--grafema-progress", (start < 0 ? 0 : start) + "%");
                    setTimeout((() => {
                        el.style.setProperty("--grafema-transition", " width " + duration);
                        el.style.setProperty("--grafema-progress", (end > 100 ? 100 : end) + "%");
                    }), 500);
                    observer.unobserve(el);
                }
            }));
        })).observe(el);
    }));
    directive("select", ((el, expression, attribute, x, component) => {
        const settings = {
            showSearch: false,
            hideSelected: false,
            closeOnSelect: true
        };
        if (el.hasAttribute("multiple")) {
            settings.hideSelected = true;
            settings.closeOnSelect = false;
        }
        const custom = JSON.parse(expression || "{}");
        if (typeof custom === "object") {
            Object.assign(settings, custom);
        }
        try {
            new SlimSelect({
                settings: settings,
                select: el,
                data: Array.from(el.options).reduce(((acc, option) => {
                    let image = option.getAttribute("data-image"), icon = option.getAttribute("data-icon"), description = option.getAttribute("data-description") || "";
                    let images = image ? `<img src="${image}" alt />` : "", icons = icon ? `<i class="${icon}"></i>` : "", descriptions = description ? `<span class="ss-description">${description}</span>` : "", html = `${images}${icons}<span class="ss-text">${option.text}${descriptions}</span>`;
                    let optionData = {
                        text: option.text,
                        value: option.value,
                        html: html,
                        selected: option.selected,
                        display: true,
                        disabled: false,
                        mandatory: false,
                        placeholder: false,
                        class: "",
                        style: "",
                        data: {}
                    };
                    if (option.parentElement.tagName === "OPTGROUP") {
                        const optgroupLabel = option.parentElement.getAttribute("label");
                        const optgroup = acc.find((item => item.label === optgroupLabel));
                        if (optgroup) {
                            optgroup.options.push(optionData);
                        } else {
                            acc.push({
                                label: optgroupLabel,
                                options: [ optionData ]
                            });
                        }
                    } else {
                        acc.push(optionData);
                    }
                    return acc;
                }), [])
            });
        } catch {
            console.error("The SlimSelect library is not connected");
        }
    }));
    directive("starter", ((el, expression, attribute, x, component) => {}));
    method("copy", ((e, el) => subject => {
        window.navigator.clipboard.writeText(subject).then((() => {
            let classes = "ph-copy ph-check".split(" ");
            classes.forEach((s => el.classList.toggle(s)));
            setTimeout((() => classes.forEach((s => el.classList.toggle(s)))), 1e3);
        }), (() => {
            console.log("Your browser is not support clipboard!");
        }));
    }));
    let seconds = 0, isCountingDown = false;
    method("countdown", (() => ({
        start: (initialSeconds, processCallback, endCallback) => {
            if (isCountingDown) {
                return;
            }
            seconds = initialSeconds;
            isCountingDown = true;
            function countdown() {
                processCallback && processCallback(true);
                if (seconds === 0) {
                    endCallback && endCallback(true);
                    isCountingDown = false;
                } else {
                    seconds--;
                    setTimeout(countdown, 1e3);
                }
            }
            countdown();
        },
        second: seconds
    })));
    let stream = null;
    method("stream", (() => ({
        check(refs) {
            let canvas = refs.canvas, video = refs.video, image = refs.image;
            if (!canvas) {
                console.error("Canvas element is undefined");
                return false;
            }
            if (!video) {
                console.error("Video for selfie preview is undefined");
                return false;
            }
            if (!image) {
                console.error("Image for output selfie is undefined");
                return false;
            }
        },
        isVisible(element) {
            const styles = window.getComputedStyle(element);
            if (styles) {
                return !(styles.visibility === "hidden" || styles.display === "none" || parseFloat(styles.opacity) === 0);
            }
            return false;
        },
        start(refs) {
            let video = refs.video;
            const observer = new MutationObserver((mutations => {
                for (let mutation of mutations) {
                    if (mutation.target === document.body && !stream) {
                        setTimeout((async () => {
                            if (this.isVisible(video)) {
                                if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                                    video.srcObject = stream = await navigator.mediaDevices.getUserMedia({
                                        video: true
                                    });
                                } else {
                                    console.error("The browser does not support the getUserMedia API");
                                }
                            }
                        }), 500);
                    }
                }
            }));
            observer.observe(document, {
                childList: true,
                subtree: true,
                attributes: true
            });
        },
        snapshot(refs) {
            this.check(refs);
            this.start(refs);
            let canvas = refs.canvas, video = refs.video, image = refs.image;
            let width = video.offsetWidth, height = video.offsetHeight;
            let imageStyles = window.getComputedStyle(image), imageWidth = parseInt(imageStyles.width, 10), imageHeight = parseInt(imageStyles.height, 10);
            canvas.width = imageWidth;
            canvas.height = imageHeight;
            let offsetTop = (height - imageHeight) / 2, offsetLeft = (width - imageWidth) / 2;
            let ctx = canvas.getContext("2d");
            ctx.imageSmoothingQuality = "low";
            let scale = height / imageHeight;
            console.log((offsetTop + offsetLeft) / 2);
            ctx.drawImage(video, offsetLeft * 1.5, offsetTop * 1.5, height * 1.5, height * 1.5, 0, 0, imageWidth, imageHeight);
            let imageData = canvas.toDataURL("image/png");
            if (imageData) {
                image.src = imageData;
                ctx.clearRect(0, 0, canvas.width, canvas.height);
            }
            return imageData;
        },
        stop() {
            if (stream) {
                stream.getTracks().forEach((track => track.stop()));
            }
            stream = null;
        }
    })));
    method("password", (() => ({
        min: {
            lowercase: 2,
            uppercase: 2,
            special: 2,
            digit: 2,
            length: 12
        },
        valid: {
            lowercase: false,
            uppercase: false,
            special: false,
            digit: false,
            length: false
        },
        charsets: {
            lowercase: "abcdefghijklmnopqrstuvwxyz",
            uppercase: "ABCDEFGHIJKLMNOPQRSTUVWXYZ",
            special: "!@#$%^&*(){|}~",
            digit: "0123456789"
        },
        switch(value) {
            return !!!value;
        },
        check(value) {
            let matchCount = 0;
            let totalCount = 0;
            for (const charset in this.charsets) {
                let requiredCount = this.min[charset], charsetRegex = new RegExp(`[${this.charsets[charset]}]`, "g"), charsetCount = (value.match(charsetRegex) || []).length;
                matchCount += Math.min(charsetCount, requiredCount);
                totalCount += requiredCount;
                this.valid[charset] = charsetCount >= requiredCount;
            }
            if (value.length >= this.min.length) {
                matchCount += 1;
                totalCount += 1;
                this.valid.length = value.length >= this.min.length;
            }
            return Object.assign({
                progress: totalCount === 0 ? totalCount : matchCount / totalCount * 100
            }, this.valid);
        },
        generate() {
            let password = "", types = Object.keys(this.charsets);
            types.forEach((type => {
                let count = Math.max(this.min[type], 0), charset = this.charsets[type];
                for (let i = 0; i < count; i++) {
                    let randomIndex = Math.floor(Math.random() * charset.length);
                    password += charset[randomIndex];
                }
            }));
            while (password.length < this.min.length) {
                let randomIndex = Math.floor(Math.random() * types.length), charType = types[randomIndex], charset = this.charsets[charType], randomCharIndex = Math.floor(Math.random() * charset.length);
                password += charset[randomCharIndex];
            }
            this.check(password);
            return this.shuffle(password);
        },
        shuffle(password) {
            let array = password.split("");
            let currentIndex = array.length;
            let temporaryValue, randomIndex;
            while (currentIndex !== 0) {
                randomIndex = Math.floor(Math.random() * currentIndex);
                currentIndex -= 1;
                temporaryValue = array[currentIndex];
                array[currentIndex] = array[randomIndex];
                array[randomIndex] = temporaryValue;
            }
            return array.join("");
        }
    })));
    method("modal", ((e, el) => ({
        open: (id, animation) => {
            setTimeout((() => {
                let modal = document.getElementById(id);
                if (modal) {
                    modal.classList.add("is-active", animation || "fade");
                }
                document.body.style.overflow = "hidden";
            }), 25);
        },
        close: animation => {
            let modal = el.closest(".modal");
            if (modal !== null && modal.classList.contains("is-active")) {
                modal.classList.remove("is-active", animation || "fade");
                document.body.style.overflow = "";
            }
        }
    })));
    method("default", ((e, el) => (url, options = {}, callback) => {}));
    window.x = scripts_x;
    window.x.start();
})();