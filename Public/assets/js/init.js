const build = (data, index) => {
    const { tags = 'div', children, content, event, attributes } = data;
    const elements = document.createElement(tags);
    event && elements.addEventListener(event.type, e => event.callback(e));
    attributes && attributes.map(attr => elements.setAttribute(...attr));
    content && (elements.innerHTML = content);
    children && children.map(child => elements.appendChild(build(child, index)));
    return elements;
};
const buildJson = (element) => {
    const obj = {};
    const cloneElement = element.cloneNode(true);
    const { tagName, children, attributes } = element;
    [...cloneElement.children].map(child => cloneElement.removeChild(child));
    obj["tags"] = tagName.toLowerCase();
    cloneElement.innerText && (obj["content"] = cloneElement.innerText);
    obj["attributes"] = [...attributes].map(item => [item.name, item.value]);
    [...children].length > 0 && (obj["children"] = [...children].map(child => buildJson(child)));
    return obj;
};
const selector = (element, root) => {
    const select = root || document;
    return [...select.querySelectorAll(element)]
};
const useEvent = (element, eventName, callback, removeEvent = true) => {
    const elem = element || document;
    const isSupported = elem && elem.addEventListener;
    if (!isSupported) return;
    const eventListner = event => callback(event);
    elem.addEventListener(eventName, eventListner, { passive: false });
};
const info = (elem) => {
    var box = elem.getBoundingClientRect();
    var body = document.body;
    var docEl = document.documentElement;
    var scrollTop = window.pageYOffset || docEl.scrollTop || body.scrollTop;
    var scrollLeft = window.pageXOffset || docEl.scrollLeft || body.scrollLeft;
    var clientTop = docEl.clientTop || body.clientTop || 0;
    var clientLeft = docEl.clientLeft || body.clientLeft || 0;
    var top = box.top + scrollTop - clientTop;
    var left = box.left + scrollLeft - clientLeft;
    return { top: Math.round(top), left: Math.round(left) };
};
const calls = (blocks, callback, options = {}) => {
    const { event, inView } = {
        event: 'DOMContentLoaded',
        ...options,
    };
    window.addEventListener(event, () => {
        if (window[blocks]) return;
        callback(blocks);
        window[blocks] = true;
    });
};

console.log('init');

window.cegos_scripts = { build, selector, buildJson, info, useEvent, calls, ...window.cegos_scripts};