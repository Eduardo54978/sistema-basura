class ElementFinder {
    find(selector) {
        try {
            return document.querySelector(selector);
        } catch (error) {
            return null;
        }
    }
    
    findAll(selector) {
        try {
            return document.querySelectorAll(selector);
        } catch (error) {
            return [];
        }
    }

    findByText(texto) {
        const elementos = Array.from(document.querySelectorAll('*'));
        for (let elem of elementos) {
            const tagName = elem.tagName.toLowerCase();
            const isInteractive = ['button', 'a', 'input', 'select', 'textarea'].includes(tagName);
            
            if (elem.children.length === 0 || isInteractive) {
                const textoElem = elem.textContent.trim();
                if (textoElem.includes(texto)) {
                    return elem;
                }
            }
        }
        return null;
    }

    findByPlaceholder(texto) {
        return document.querySelector(`[placeholder*="${texto}"]`);
    }

    findByName(nombre) {
        return document.querySelector(`[name="${nombre}"]`);
    }

    findByLabel(label) {
        return document.querySelector(`[aria-label*="${label}"]`);
    }
}
class ElementInfo {
    isVisible(elem) {
        if (!elem) return false;
        const style = window.getComputedStyle(elem);
        return style.display !== 'none' && 
               style.visibility !== 'hidden' && 
               style.opacity !== '0' &&
               elem.offsetWidth > 0 && elem.offsetHeight > 0;
    }
    
    getFullInfo(elem) {
        if (!elem) return null;
        const rect = elem.getBoundingClientRect();
        const styles = window.getComputedStyle(elem);
        
        return {
            tag: elem.tagName.toLowerCase(),
            id: elem.id || null,
            text: elem.textContent.trim(),
            value: elem.value || null,
            size: { width: rect.width, height: rect.height },
            visible: this.isVisible(elem),
            enabled: !elem.disabled,
            styles: { display: styles.display, visibility: styles.visibility }
        };
    }

    getPageInfo() {
        return {
            title: document.title,
            url: window.location.href,
            totalElements: document.querySelectorAll('*').length,
            inputs: document.querySelectorAll('input').length,
            buttons: document.querySelectorAll('button').length,
            links: document.querySelectorAll('a').length,
            forms: document.querySelectorAll('form').length
        };
    }
}
class Parser {
    constructor(autolib) {
        this.bot = autolib;
        this.comandosRegexp = [
            { 
                reg: /^(escribe|escribir|teclea)\s+"([^"]+)"\s+en\s+(.+)$/, 
                accion: 'type', 
                params: (m) => [this.bot._resolverSelector(m[3]), m[2]] 
            },
            { 
                reg: /^(click|clic|presiona|pulsa)\s+en\s+(.+)$/, 
                accion: 'click', 
                params: (m) => [this.bot._resolverSelector(m[2])] 
            },
            { 
                reg: /^(double click|doble clic)\s+en\s+(.+)$/, 
                accion: 'doubleClick', 
                params: (m) => [this.bot._resolverSelector(m[2])] 
            },
            { 
                reg: /^(right click|clic derecho)\s+en\s+(.+)$/, 
                accion: 'rightClick', 
                params: (m) => [this.bot._resolverSelector(m[2])] 
            },
            { 
                reg: /^(scroll|desplaza)\s+(arriba|abajo|up|down)$/, 
                accion: 'scroll', 
                params: (m) => [m[2]] 
            },
            { 
                reg: /^(espera|esperar|wait)\s+(\d+)\s*(segundos?|segs?|s)?$/, 
                accion: 'wait', 
                params: (m) => [parseInt(m[2])] 
            },
            { 
                reg: /^(obtener|obtiene|dame|trae)\s+(texto|valor)\s+de\s+(.+)$/, 
                accion: 'get', 
                params: (m) => [this.bot._resolverSelector(m[3]), m[2]] 
            },
            { 
                reg: /^(cuenta|contar)\s+(.+)$/, 
                accion: 'count', 
                params: (m) => [this.bot._resolverSelector(m[2])] 
            },
            { 
                reg: /^(verifica|verificar|check)\s+si\s+existe\s+(.+)$/, 
                accion: 'exists', 
                params: (m) => [this.bot._resolverSelector(m[2])] 
            },
            { 
                reg: /^(agregar|añadir)\s+clase\s+"([^"]+)"\s+a\s+(.+)$/, 
                accion: 'addClass', 
                params: (m) => [this.bot._resolverSelector(m[3]), m[2]] 
            }
        ];
    }

    parse(comando) {
        comando = comando.trim().toLowerCase();
        for (const item of this.comandosRegexp) {
            const match = comando.match(item.reg);
            if (match) {
                return {
                    accion: item.accion,
                    params: item.params(match)
                };
            }
        }
        throw new Error(`Comando no reconocido: "${comando}"`);
    }
}
class ActionExecutor {
    constructor(autolib) {
        this.bot = autolib;
    }
    
    async executeParsed(parsedCommand) {
        const { accion, params } = parsedCommand;
        
        switch (accion) {
            case 'click':
                this.bot.click(...params);
                break;
            case 'doubleClick':
                this.bot.doubleClick(...params);
                break;
            case 'rightClick':
                this.bot.rightClick(...params);
                break;
            case 'type':
                this.bot.type(...params);
                break;
            case 'scroll':
                this.bot.scroll(...params);
                break;
            case 'wait':
                await this.bot.wait(...params);
                return true;
            case 'get':
                return params[1] === 'texto' ? this.bot.getText(params[0]) : this.bot.getValue(params[0]);
            case 'count':
                return this.bot.count(...params);
            case 'exists':
                return this.bot.exists(...params);
            case 'addClass':
                this.bot.addClass(...params);
                break;
            default:
                throw new Error(`Acción no implementada para: ${accion}`);
        }
        return true;
    }
}
class AutoLib {
    constructor(options = {}) {
        this.version = '3.0.0';
        this.debug = options.debug || false;
        this.historial = [];
        
        this.finder = new ElementFinder();
        this.info = new ElementInfo();
        this.parser = new Parser(this);
        this.executor = new ActionExecutor(this);
        
        if (this.debug) {
            console.log(`[AutoLib Debug] AutoLib v${this.version} iniciada en modo debug`);
        }
    }

    async execute(comando) {
        if (this.debug) {
            console.log(`[AutoLib Debug] Ejecutando comando: "${comando}"`);
        }
        const parsed = this.parser.parse(comando);
        const resultado = await this.executor.executeParsed(parsed);
        
        if (!['wait', 'get', 'count', 'exists'].includes(parsed.accion)) {
            this._saveToHistory(comando);
        }
        return resultado;
    }

    async runSequence(comandos) {
        const resultados = [];
        for (let i = 0; i < comandos.length; i++) {
            try {
                const resultado = await this.execute(comandos[i]);
                resultados.push({ comando: comandos[i], exito: true, resultado: resultado });
            } catch (error) {
                resultados.push({ comando: comandos[i], exito: false, error: error.message });
            }
        }
        return resultados;
    }

    click(selector) {
        const elem = this.finder.find(selector);
        if (!elem) throw new Error(`Click Fallido: Elemento "${selector}" no encontrado.`);
        elem.click();
        return true;
    }
    
    doubleClick(selector) {
        const elem = this.finder.find(selector);
        if (!elem) throw new Error(`Doble Click Fallido: Elemento "${selector}" no encontrado.`);
        const event = new MouseEvent('dblclick', { view: window, bubbles: true, cancelable: true });
        elem.dispatchEvent(event);
        return true;
    }
    
    rightClick(selector) {
        const elem = this.finder.find(selector);
        if (!elem) throw new Error(`Click Derecho Fallido: Elemento "${selector}" no encontrado.`);
        const event = new MouseEvent('contextmenu', { view: window, bubbles: true, cancelable: true });
        elem.dispatchEvent(event);
        return true;
    }

    type(selector, texto) {
        const elem = this.finder.find(selector);
        if (!elem) throw new Error(`Type Fallido: Elemento "${selector}" no encontrado.`);
        elem.value = texto;
        elem.dispatchEvent(new Event('input', { bubbles: true }));
        elem.dispatchEvent(new Event('change', { bubbles: true }));
        return true;
    }
    
    scroll(direccion, cantidad = 300) {
        if (direccion === 'arriba' || direccion === 'up') {
            window.scrollBy({ top: -cantidad, behavior: 'smooth' });
        } else {
            window.scrollBy({ top: cantidad, behavior: 'smooth' });
        }
        return true;
    }
    
    wait(segundos) {
        return new Promise(resolve => {
            setTimeout(resolve, segundos * 1000);
        });
    }

    addClass(selector, clase) {
        const elem = this.finder.find(selector);
        if (!elem) throw new Error(`Add Class Fallido: Elemento "${selector}" no encontrado.`);
        elem.classList.add(clase);
        return true;
    }
    
    find(selector) { return this.finder.find(selector); }
    count(selector) { return this.finder.findAll(selector).length; }
    getText(selector) { return this.finder.find(selector)?.textContent ?? null; }
    getValue(selector) { return this.finder.find(selector)?.value ?? null; }
    getFullInfo(selector) { return this.info.getFullInfo(this.finder.find(selector)); }
    exists(selector) { return this.finder.find(selector) !== null; }
    getPageInfo() { return this.info.getPageInfo(); }
    
    _resolverSelector(descripcion) {
        descripcion = descripcion.trim();
        if (descripcion.match(/^[.#\[]/)) return descripcion;
        
        const matchComillas = descripcion.match(/^"([^"]+)"$/);
        if (matchComillas) {
            const elem = this.finder.findByText(matchComillas[1]);
            return elem ? (elem.id ? `#${elem.id}` : elem.tagName.toLowerCase()) : descripcion;
        }
        
        const elem = this.finder.findByText(descripcion);
        if (elem) {
            return elem.id ? `#${elem.id}` : elem.tagName.toLowerCase();
        }
        
        return descripcion;
    }

    _saveToHistory(accion) {
        this.historial.push({ accion: accion, timestamp: new Date().toISOString() });
        if (this.historial.length > 50) {
            this.historial.shift();
        }
    }
    
    getStats() {
        return {
            version: this.version,
            accionesEjecutadas: this.historial.length,
            elementosEnPagina: document.querySelectorAll('*').length
        };
    }
    
    getHistory() { return this.historial; }
}
window.AutoLib = AutoLib;

console.log('✅ Librería de Gramáticas cargada correctamente (versión unificada)');