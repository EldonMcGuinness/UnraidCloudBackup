/*
 *  querystring.js - v2.0
 *  Querystring utility in Javascript
 *  https://github.com/EldonMcGuinness/querystring.js
 *
 *  Made by Eldon McGuinness
 *  Optimized by notchyves
 *  Under MIT License
 *

    query(type, str)
        - purpose: This is the core function, renamed to support multiple query types.
        - type: This defines the type of query being decoded: 
            - "?", this denotes it is a querystring
            - "#", this denotes it is a hash string
        - str: This is the string to be parsed.

    querystring(str)
        - purpose: This function is utilized to parse query strings.
        - str: (optional) This is the string to be parsed.

    queryhash(str)
        - purpose: This function is utilized to parse hash strings.
        - str: (optional) This is the string to be parsed. 
*/

function query(type, str) {
    const qso = {};
    
    const qs = str.substring(str.indexOf(type) + 1)
        .replace(/;/g, "&")
        .replace(/&&+/g, "&")
        .replace(/&$/, "")
        .split("&");

    qs.forEach(part => {
        if (part === "") return;
        const [key, val] = part.split("=").map(decodeURIComponent);
        const value = val === "" ? null : val;
        if (qso.hasOwnProperty(key)) {
            qso[key] = [].concat(qso[key], value);
        } else {
            qso[key] = value;
        }
    });

    return qso;
}

function querystring(str = document.location.search) {
    return query("?", str);
}

function queryhash(str = document.location.hash) {
    return query("#", str);
}