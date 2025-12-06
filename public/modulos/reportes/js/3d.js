const datosElement = document.getElementById('datos-json');
const datos = JSON.parse(datosElement.textContent);
const svg = document.getElementById('grafico3d');
const width = 800;
const height = 500;
const margin = { top: 60, right: 40, bottom: 60, left: 100 };
const categorias = ['plastico', 'vidrio', 'metal', 'papel', 'organico'];
const colores = {
    'plastico': '#FF6B6B',
    'vidrio': '#4ECDC4',
    'metal': '#95A5A6',
    'papel': '#F4D03F',
    'organico': '#52B788'
};
let datosCategoria = {};
categorias.forEach(cat => {
    datosCategoria[cat] = 0;
});
datos.forEach(dato => {
    datosCategoria[dato.categoria] = parseInt(dato.total);
});
const valores = Object.values(datosCategoria);
const maxValor = valores.length > 0 ? Math.max(...valores, 10) : 10;
const escalaY = (height - margin.top - margin.bottom) / maxValor;
const anchoBarra = (width - margin.left - margin.right) / categorias.length - 40;

console.log('Datos procesados:', datosCategoria);
console.log('Máximo valor:', maxValor);
function dibujarGrafico() {
    svg.innerHTML = '';
    const rectFondo = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
    rectFondo.setAttribute('width', width);
    rectFondo.setAttribute('height', height);
    rectFondo.setAttribute('fill', '#f8f9fa');
    svg.appendChild(rectFondo);
    const titulo = document.createElementNS('http://www.w3.org/2000/svg', 'text');
    titulo.setAttribute('x', width / 2);
    titulo.setAttribute('y', 30);
    titulo.setAttribute('text-anchor', 'middle');
    titulo.setAttribute('fill', '#11998e');
    titulo.setAttribute('font-size', '20');
    titulo.setAttribute('font-weight', 'bold');
    titulo.textContent = 'Detecciones por Categoría - Mes Seleccionado';
    svg.appendChild(titulo);
    
    // Ejes
    const ejeX = document.createElementNS('http://www.w3.org/2000/svg', 'line');
    ejeX.setAttribute('x1', margin.left);
    ejeX.setAttribute('y1', height - margin.bottom);
    ejeX.setAttribute('x2', width - margin.right);
    ejeX.setAttribute('y2', height - margin.bottom);
    ejeX.setAttribute('stroke', '#333');
    ejeX.setAttribute('stroke-width', '2');
    svg.appendChild(ejeX);
    
    const ejeY = document.createElementNS('http://www.w3.org/2000/svg', 'line');
    ejeY.setAttribute('x1', margin.left);
    ejeY.setAttribute('y1', margin.top);
    ejeY.setAttribute('x2', margin.left);
    ejeY.setAttribute('y2', height - margin.bottom);
    ejeY.setAttribute('stroke', '#333');
    ejeY.setAttribute('stroke-width', '2');
    svg.appendChild(ejeY);
    categorias.forEach((cat, i) => {
        const x = margin.left + (i * (anchoBarra + 40)) + 20;
        const valor = datosCategoria[cat] || 0;
        const alturaBarra = valor * escalaY;
        const y = height - margin.bottom - alturaBarra;
        
        console.log(`${cat}: valor=${valor}, altura=${alturaBarra}, y=${y}`);
        const barra = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
        barra.setAttribute('x', x);
        barra.setAttribute('y', height - margin.bottom);
        barra.setAttribute('width', anchoBarra);
        barra.setAttribute('height', 0);
        barra.setAttribute('fill', colores[cat]);
        barra.setAttribute('opacity', '0.8');
        barra.setAttribute('rx', '5');
        svg.appendChild(barra);
        setTimeout(() => {
            barra.setAttribute('y', y);
            barra.setAttribute('height', alturaBarra);
            barra.style.transition = 'all 1s ease-out';
        }, i * 100);
        barra.addEventListener('mouseenter', function() {
            this.setAttribute('opacity', '1');
        });
        barra.addEventListener('mouseleave', function() {
            this.setAttribute('opacity', '0.8');
        });
        
        // Etiqueta categoría
        const texto = document.createElementNS('http://www.w3.org/2000/svg', 'text');
        texto.setAttribute('x', x + anchoBarra / 2);
        texto.setAttribute('y', height - margin.bottom + 25);
        texto.setAttribute('text-anchor', 'middle');
        texto.setAttribute('fill', '#333');
        texto.setAttribute('font-size', '14');
        texto.setAttribute('font-weight', 'bold');
        texto.textContent = cat.charAt(0).toUpperCase() + cat.slice(1);
        svg.appendChild(texto);
        if (valor > 0) {
            const valorTexto = document.createElementNS('http://www.w3.org/2000/svg', 'text');
            valorTexto.setAttribute('x', x + anchoBarra / 2);
            valorTexto.setAttribute('y', y - 10);
            valorTexto.setAttribute('text-anchor', 'middle');
            valorTexto.setAttribute('fill', colores[cat]);
            valorTexto.setAttribute('font-size', '18');
            valorTexto.setAttribute('font-weight', 'bold');
            valorTexto.setAttribute('opacity', '0');
            valorTexto.textContent = valor;
            svg.appendChild(valorTexto);
            
            setTimeout(() => {
                valorTexto.setAttribute('opacity', '1');
                valorTexto.style.transition = 'opacity 0.5s ease-in';
            }, 800 + (i * 100));
        }
    });
    const labelY = document.createElementNS('http://www.w3.org/2000/svg', 'text');
    labelY.setAttribute('x', 30);
    labelY.setAttribute('y', height / 2);
    labelY.setAttribute('text-anchor', 'middle');
    labelY.setAttribute('fill', '#666');
    labelY.setAttribute('font-size', '14');
    labelY.setAttribute('transform', `rotate(-90, 30, ${height / 2})`);
    labelY.textContent = 'Cantidad Detectada';
    svg.appendChild(labelY);
}
if (svg) {
    console.log('SVG encontrado, dibujando gráfico...');
    dibujarGrafico();
} else {
    console.error('No se encontró el elemento SVG con id="grafico3d"');
}