const datosElement = document.getElementById('datos-json');
const datos = JSON.parse(datosElement.textContent);
const svg = document.getElementById('graficoFecha');
const width = 800;
const height = 400;
const margin = { top: 40, right: 40, bottom: 80, left: 60 };
const nombresMeses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
let datosMeses = Array(12).fill(0);
datos.forEach(dato => {
    datosMeses[dato.mes - 1] = dato.total;
});
const maxValor = Math.max(...datosMeses, 1);
const escalaY = (height - margin.top - margin.bottom) / maxValor;
const espacioX = (width - margin.left - margin.right) / 11; 
while (svg.firstChild) {
    svg.removeChild(svg.firstChild);
}
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
let puntos = '';
datosMeses.forEach((valor, i) => {
    const x = margin.left + (i * espacioX);
    const y = height - margin.bottom - (valor * escalaY);
    puntos += `${x},${y} `;
});
const polygon = document.createElementNS('http://www.w3.org/2000/svg', 'polygon');
const puntosArea = puntos + `${width - margin.right},${height - margin.bottom} ${margin.left},${height - margin.bottom}`;
polygon.setAttribute('points', puntosArea.trim());
polygon.setAttribute('fill', '#11998e');
polygon.setAttribute('opacity', '0.2');
svg.appendChild(polygon);
const polyline = document.createElementNS('http://www.w3.org/2000/svg', 'polyline');
polyline.setAttribute('points', puntos.trim());
polyline.setAttribute('fill', 'none');
polyline.setAttribute('stroke', '#11998e');
polyline.setAttribute('stroke-width', '3');
svg.appendChild(polyline);
datosMeses.forEach((valor, i) => {
    const x = margin.left + (i * espacioX);
    const y = height - margin.bottom - (valor * escalaY);
    const circulo = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
    circulo.setAttribute('cx', x);
    circulo.setAttribute('cy', y);
    circulo.setAttribute('r', '6');
    circulo.setAttribute('fill', '#38ef7d');
    circulo.setAttribute('stroke', '#11998e');
    circulo.setAttribute('stroke-width', '2');
    
    circulo.addEventListener('mouseenter', function() {
        this.setAttribute('r', '8');
    });
    circulo.addEventListener('mouseleave', function() {
        this.setAttribute('r', '6');
    });
    
    svg.appendChild(circulo);
    const texto = document.createElementNS('http://www.w3.org/2000/svg', 'text');
    texto.setAttribute('x', x);
    texto.setAttribute('y', height - margin.bottom + 20);
    texto.setAttribute('text-anchor', 'middle');
    texto.setAttribute('fill', '#333');
    texto.setAttribute('font-size', '12');
    texto.setAttribute('font-weight', 'bold');
    texto.textContent = nombresMeses[i];
    svg.appendChild(texto);
    if (valor > 0) {
        const valorTexto = document.createElementNS('http://www.w3.org/2000/svg', 'text');
        valorTexto.setAttribute('x', x);
        valorTexto.setAttribute('y', y - 10);
        valorTexto.setAttribute('text-anchor', 'middle');
        valorTexto.setAttribute('fill', '#11998e');
        valorTexto.setAttribute('font-size', '12');
        valorTexto.setAttribute('font-weight', 'bold');
        valorTexto.textContent = valor;
        svg.appendChild(valorTexto);
    }
});
const titulo = document.createElementNS('http://www.w3.org/2000/svg', 'text');
titulo.setAttribute('x', width / 2);
titulo.setAttribute('y', 25);
titulo.setAttribute('text-anchor', 'middle');
titulo.setAttribute('fill', '#11998e');
titulo.setAttribute('font-size', '18');
titulo.setAttribute('font-weight', 'bold');
titulo.textContent = 'Detecciones por Mes del Año';
svg.appendChild(titulo);