class PDFGenerator {
     constructor(config = {}) {
       this.config = {
         pageSize: 'A4',
         orientation: 'portrait',
         unit: 'mm',
         compress: true,
         imageQuality: 0.92,
         margins: { top: 40, right: 40, bottom: 40, left: 40 },
         ...config
       };
   
       this.doc = null;
       this.currentPage = null;
       this.fontManager = new FontManager();
       this.styleEngine = new StyleEngine();
       this.layoutEngine = new LayoutEngine();
       this.contentQueue = [];
       this.pageCount = 0;
       this.metadata = {
         title: '',
         author: '',
         subject: '',
         keywords: [],
         creator: 'PDF Generator v1.0'
       };
   
       this.initUnitConversions();
       this.initStandardPageSizes();
     }
   
     initUnitConversions() {
       this.unitConversionRatios = {
         mm: 0.264583,
         cm: 2.64583,
         in: 96,
         pt: 1.333333,
         px: 1
       };
     }
   
     initStandardPageSizes() {
       this.pageSizePresets = {
         A0: { width: 841, height: 1189 },
         A1: { width: 594, height: 841 },
         A2: { width: 420, height: 594 },
         A3: { width: 297, height: 420 },
         A4: { width: 210, height: 297 },
         Letter: { width: 215.9, height: 279.4 },
         Legal: { width: 215.9, height: 355.6 },
         Tabloid: { width: 279.4, height: 431.8 }
       };
     }
   
     async initialize() {
       await this.fontManager.loadSystemFonts();
       this.createNewDocument();
     }
   
     createNewDocument() {
       this.doc = new jsPDF({
         orientation: this.config.orientation,
         unit: this.config.unit,
         format: this.config.pageSize,
         compress: this.config.compress
       });
       this.applyDocumentMetadata();
     }
   
     applyDocumentMetadata() {
       this.doc.setProperties({
         title: this.metadata.title,
         author: this.metadata.author,
         subject: this.metadata.subject,
         keywords: this.metadata.keywords.join(', '),
         creator: this.metadata.creator
       });
     }
   
     convertUnits(value, fromUnit, toUnit) {
       const pxValue = value * this.unitConversionRatios[fromUnit];
       return pxValue / this.unitConversionRatios[toUnit];
     }
   
     addPage(size = this.config.pageSize) {
       this.doc.addPage(size);
       this.pageCount++;
     }
   
     async generate() {
       await this.processContentQueue();
       return this.doc.output('blob');
     }
   
     async processContentQueue() {
       for (const item of this.contentQueue) {
         switch (item.type) {
           case 'text':
             await this.processTextItem(item);
             break;
           case 'image':
             await this.processImageItem(item);
             break;
           case 'vector':
             await this.processVectorItem(item);
             break;
           case 'table':
             await this.processTableItem(item);
             break;
         }
       }
     }
   
     async processTextItem(item) {
       this.doc.setFont(item.fontFamily);
       this.doc.setFontSize(item.fontSize);
       this.doc.setTextColor(item.color);
       this.doc.text(item.content, item.x, item.y, {
         align: item.align,
         baseline: item.baseline
       });
     }
   
     async processImageItem(item) {
       const img = await this.loadImage(item.src);
       this.doc.addImage(img, item.format, item.x, item.y, item.width, item.height);
     }
   
     async loadImage(src) {
       return new Promise((resolve, reject) => {
         const img = new Image();
         img.crossOrigin = 'Anonymous';
         img.onload = () => resolve(img);
         img.onerror = reject;
         img.src = src;
       });
     }
   
     setMetadata(metadata) {
       this.metadata = { ...this.metadata, ...metadata };
     }
   
     addContent(content) {
       this.contentQueue.push(content);
     }
   
     destroy() {
       this.doc = null;
       this.contentQueue = [];
       this.pageCount = 0;
     }
   }
   
   class FontManager {
     constructor() {
       this.registeredFonts = new Map();
       this.activeFont = null;
     }
   
     async loadFont(fontDefinition) {
       const fontFace = new FontFace(
         fontDefinition.family,
         `url(${fontDefinition.src})`,
         fontDefinition.descriptors
       );
   
       try {
         await fontFace.load();
         document.fonts.add(fontFace);
         this.registerFontMetrics(fontDefinition);
       } catch (error) {
         throw new Error(`Font loading failed: ${error.message}`);
       }
     }
   
     registerFontMetrics(fontDefinition) {
       const canvas = document.createElement('canvas');
       const ctx = canvas.getContext('2d');
       ctx.font = `100px ${fontDefinition.family}`;
       
       const metrics = {
         ascender: 0.8,
         descender: 0.2,
         lineGap: 0.1,
         xHeight: 0.5,
         capHeight: 0.7,
         unitsPerEm: 1000
       };
   
       this.registeredFonts.set(fontDefinition.family, {
         family: fontDefinition.family,
         weights: fontDefinition.weights || [400],
         styles: fontDefinition.styles || ['normal'],
         metrics: metrics
       });
     }
   
     async loadSystemFonts() {
       const systemFonts = [
         'Arial', 'Helvetica', 'Times New Roman', 
         'Courier New', 'Verdana', 'Georgia'
       ];
   
       for (const font of systemFonts) {
         this.registeredFonts.set(font, {
           family: font,
           weights: [400, 700],
           styles: ['normal', 'italic'],
           metrics: this.getDefaultMetrics()
         });
       }
     }
   
     getDefaultMetrics() {
       return {
         ascender: 0.75,
         descender: 0.25,
         lineGap: 0.15,
         xHeight: 0.48,
         capHeight: 0.65,
         unitsPerEm: 1000
       };
     }
   
     setActiveFont(family, weight = 400, style = 'normal') {
       if (!this.registeredFonts.has(family)) {
         throw new Error(`Font family ${family} not registered`);
       }
   
       const font = this.registeredFonts.get(family);
       if (!font.weights.includes(weight)) {
         throw new Error(`Weight ${weight} not available for ${family}`);
       }
   
       if (!font.styles.includes(style)) {
         throw new Error(`Style ${style} not available for ${family}`);
       }
   
       this.activeFont = {
         family: family,
         weight: weight,
         style: style,
         metrics: font.metrics
       };
     }
   
     calculateTextWidth(text, fontSize) {
       if (!this.activeFont) return 0;
       const canvas = document.createElement('canvas');
       const ctx = canvas.getContext('2d');
       ctx.font = `${this.activeFont.weight} ${fontSize}px ${this.activeFont.family}`;
       return ctx.measureText(text).width;
     }
   
     getLineHeight(fontSize) {
       if (!this.activeFont) return fontSize * 1.2;
       const metrics = this.activeFont.metrics;
       return (metrics.ascender + metrics.descender + metrics.lineGap) * fontSize;
     }
   }
   
   class StyleEngine {
     constructor() {
       this.styleRules = new Map();
       this.currentStyles = {};
     }
   
     parseCSS(cssText) {
       const rules = cssText.split(/}(?=\s*[^}])/);
       for (const rule of rules) {
         const [selector, declarations] = rule.split('{');
         const cleanedSelector = selector.trim();
         const properties = this.parseDeclarations(declarations);
         this.styleRules.set(cleanedSelector, properties);
       }
     }
   
     parseDeclarations(declarations) {
       const properties = {};
       declarations = declarations.replace(/\/\*.*?\*\//g, '').trim();
       const pairs = declarations.split(';').filter(p => p.trim());
       
       for (const pair of pairs) {
         const [property, value] = pair.split(':').map(s => s.trim());
         if (property && value) {
           properties[property] = this.parseValue(value);
         }
       }
       return properties;
     }
   
     parseValue(value) {
       const numberMatch = value.match(/^([\d.]+)(px|pt|em|rem|%)$/);
       if (numberMatch) {
         return {
           value: parseFloat(numberMatch[1]),
           unit: numberMatch[2]
         };
       }
       return value;
     }
   
     getComputedStyles(element) {
       const computed = {};
       for (const [selector, styles] of this.styleRules) {
         if (this.matchesSelector(element, selector)) {
           Object.assign(computed, styles);
         }
       }
       return computed;
     }
   
     matchesSelector(element, selector) {
       try {
         return element.matches(selector);
       } catch {
         return false;
       }
     }
   }
   
   class LayoutEngine {
     constructor() {
       this.pageLayouts = new Map();
       this.currentPage = null;
     }
   
     calculateLayout(elements, pageSize) {
       const layout = {
         pages: [],
         currentPage: this.createPage(pageSize)
       };
   
       for (const element of elements) {
         if (this.needsPageBreak(element, layout.currentPage)) {
           layout.pages.push(layout.currentPage);
           layout.currentPage = this.createPage(pageSize);
         }
         layout.currentPage.elements.push(element);
       }
   
       layout.pages.push(layout.currentPage);
       return layout;
     }
   
     createPage(pageSize) {
       return {
         width: pageSize.width,
         height: pageSize.height,
         elements: [],
         styles: {}
       };
     }
   
     needsPageBreak(element, currentPage) {
       const elementHeight = element.height || 0;
       return currentPage.currentY + elementHeight > currentPage.height;
     }
   }