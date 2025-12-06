const datosElement = document.getElementById('datos-json');
const datos = JSON.parse(datosElement.textContent);

const svg = document.getElementById('graficoConteo');
const width = 800;
const height = 400;
const margin = { top: 40, right: 40, bottom: 60, left: 60 };

const colores = ['#667eea', '#764ba2', '#f093fb', '#4facfe', '#43e97b'];

const anchoBarra = (width - margin.left - margin.right) / datos.length - 20;
const maxValor = Math.max(...datos.map(d => d.total));
const escalaY = (height - margin.top - margin.bottom) / maxValor;
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
datos.forEach((dato, i) => {
    const x = margin.left + (i * (anchoBarra + 20)) + 10;
    const alturaBarra = dato.total * escalaY;
    const y = height - margin.bottom - alturaBarra;
    const rect = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
    rect.setAttribute('x', x);
    rect.setAttribute('y', y);
    rect.setAttribute('width', anchoBarra);
    rect.setAttribute('height', alturaBarra);
    rect.setAttribute('fill', colores[i % colores.length]);
    rect.setAttribute('opacity', '0.8');
    rect.addEventListener('mouseenter', function() {
        this.setAttribute('opacity', '1');
    });
    rect.addEventListener('mouseleave', function() {
        this.setAttribute('opacity', '0.8');
    });
    
    svg.appendChild(rect);
    const texto = document.createElementNS('http://www.w3.org/2000/svg', 'text');
    texto.setAttribute('x', x + anchoBarra / 2);
    texto.setAttribute('y', height - margin.bottom + 20);
    texto.setAttribute('text-anchor', 'middle');
    texto.setAttribute('fill', '#333');
    texto.setAttribute('font-size', '12');
    texto.textContent = dato.categoria;
    svg.appendChild(texto);
    const valor = document.createElementNS('http://www.w3.org/2000/svg', 'text');
    valor.setAttribute('x', x + anchoBarra / 2);
    valor.setAttribute('y', y - 10);
    valor.setAttribute('text-anchor', 'middle');
    valor.setAttribute('fill', '#333');
    valor.setAttribute('font-size', '14');
    valor.setAttribute('font-weight', 'bold');
    valor.textContent = dato.total;
    svg.appendChild(valor);
});
const tituloY = document.createElementNS('http://www.w3.org/2000/svg', 'text');
tituloY.setAttribute('x', 20);
tituloY.setAttribute('y', height / 2);
tituloY.setAttribute('text-anchor', 'middle');
tituloY.setAttribute('fill', '#666');
tituloY.setAttribute('font-size', '12');
tituloY.setAttribute('transform', `rotate(-90, 20, ${height / 2})`);
tituloY.textContent = 'Cantidad';
svg.appendChild(tituloY);