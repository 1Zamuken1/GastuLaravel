window.addEventListener("DOMContentLoaded", () => {
    let chartBalance;

    // Obtén los datos reales desde el blade
    const ingresosMes = window.dashboardData?.ingresosMes || [];
    const egresosMes = window.dashboardData?.egresosMes || [];
    const totalIngresos = window.dashboardData?.totalIngresos || 0;
    const totalEgresos = window.dashboardData?.totalEgresos || 0;
    const saldoNeto = window.dashboardData?.saldoNeto || 0;
    const totalAhorros = window.dashboardData?.totalAhorros || 0;

    // Actualizar estadísticas principales
    function actualizarEstadisticas() {
        document.getElementById("ingresos-stat").textContent = `$${Number(
            totalIngresos
        ).toLocaleString()}`;
        document.getElementById("egresos-stat").textContent = `$${Number(
            totalEgresos
        ).toLocaleString()}`;
        document.getElementById("saldo-stat").textContent = `$${Number(
            saldoNeto
        ).toLocaleString()}`;
        document.getElementById("ahorros-stat").textContent = `$${Number(
            totalAhorros
        ).toLocaleString()}`;
    }

    // Inicializar gráfico de balance
    function inicializarGraficos() {
        actualizarEstadisticas();

        chartBalance = new ApexCharts(document.querySelector("#chartBalance"), {
            series: [
                {
                    name: "Ingresos",
                    data: ingresosMes,
                },
                {
                    name: "Egresos",
                    data: egresosMes,
                },
            ],
            chart: {
                type: "line",
                height: 350,
                background: "#FFFFFF",
                toolbar: { theme: "light" },
                animations: {
                    enabled: true,
                    easing: "easeinout",
                    speed: 1500,
                    animateGradually: {
                        enabled: true,
                        delay: 150,
                    },
                    dynamicAnimation: {
                        enabled: true,
                        speed: 1000,
                    },
                },
            },
            colors: ["#4CAF50", "#d72638"],
            stroke: {
                width: 4,
                curve: "smooth",
            },
            markers: {
                size: 6,
                colors: ["#4CAF50", "#d72638"],
                strokeColors: "#333333",
                strokeWidth: 2,
            },
            xaxis: {
                categories: [
                    "Ene",
                    "Feb",
                    "Mar",
                    "Abr",
                    "May",
                    "Jun",
                    "Jul",
                    "Ago",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dic",
                ],
                labels: {
                    style: {
                        colors: "#333333",
                        fontSize: "12px",
                        fontWeight: "bold",
                    },
                },
            },
            yaxis: {
                labels: {
                    style: {
                        colors: "#333333",
                        fontSize: "12px",
                        fontWeight: "bold",
                    },
                    formatter: (val) => `$${val.toLocaleString()}`,
                },
            },
            legend: {
                labels: {
                    colors: "#333333",
                    useSeriesColors: false,
                },
            },
            grid: {
                borderColor: "#ddd",
                strokeDashArray: 3,
            },
            tooltip: {
                theme: "light",
                style: {
                    fontSize: "14px",
                    fontWeight: "bold",
                    colors: ["#333333"],
                },
                x: {
                    show: true,
                    formatter: (val) => `Mes: ${val}`,
                },
                y: {
                    formatter: function (val, { seriesIndex, w }) {
                        try {
                            const nombreSerie =
                                w.globals.seriesNames[seriesIndex] || "Valor";
                            return `${nombreSerie}: $${val.toLocaleString()}`;
                        } catch (e) {
                            return `$${val.toLocaleString()}`;
                        }
                    },
                },
            },
        });
        chartBalance.render();
    }

    inicializarGraficos();

    // Lógica de alertas (simulada, puedes personalizarla con tus propios criterios)
    const alertaDiv = document.getElementById("alerta");
    function actualizarAlerta() {
        alertaDiv.textContent = "¡Revisa tus gastos este mes!";
    }
    actualizarAlerta();

    // Lógica de recomendaciones (sin cambios)
    const recordatorioDiv = document.getElementById("recordatorio");
    const recomendaciones = {
        Transporte: [
            "Camina o usa bicicleta",
            "Comparte transporte",
            "Usa transporte público",
        ],
        Comida: ["Presupuesto semanal", "Compra a granel", "Evita comer fuera"],
        Entretenimiento: [
            "Busca actividades gratuitas",
            "Limita suscripciones",
            "Haz planes en casa",
        ],
        Servicios: [
            "Apaga luces",
            "Desconecta cargadores",
            "Reduce el aire acondicionado",
        ],
        Salud: [
            "Compara medicamentos",
            "Consulta EPS",
            "Usa beneficios del sistema",
        ],
    };
    let categoriaActual = 0;
    let indiceRecomendacion = 0;
    const categorias = Object.keys(recomendaciones);

    function actualizarRecomendacion() {
        const categoria = categorias[categoriaActual];
        const lista = recomendaciones[categoria];
        const texto = `${categoria}: ${lista[indiceRecomendacion]}`;
        recordatorioDiv.textContent = texto;

        indiceRecomendacion = (indiceRecomendacion + 1) % lista.length;
        if (indiceRecomendacion === 0) {
            categoriaActual = (categoriaActual + 1) % categorias.length;
        }
    }
    actualizarRecomendacion();
    setInterval(actualizarRecomendacion, 12000);

    // Mensajes motivacionales (sin cambios)
    const mensajeDiv = document.getElementById("mensaje");
    const mensajesApoyo = [
        "¡Vas por buen camino, sigue así!",
        "Recuerda: cada peso que ahorras cuenta.",
        "Mantén la disciplina, tu futuro financiero lo agradecerá.",
        "¡Tú puedes lograr tus metas de ahorro!",
        "Evitar gastos innecesarios te dará más tranquilidad.",
        "Revisar tus finanzas con frecuencia te da control.",
    ];
    let iMensaje = 0;

    function actualizarMensaje() {
        mensajeDiv.textContent = mensajesApoyo[iMensaje];
        iMensaje = (iMensaje + 1) % mensajesApoyo.length;
    }
    actualizarMensaje();
    setInterval(actualizarMensaje, 15000);

    // Gráfico de distribución de ahorros
    const ahorroLabels = window.dashboardData?.ahorroLabels || [];
    const ahorroData = window.dashboardData?.ahorroData || [];
    //const totalAhorros = window.dashboardData?.totalAhorros || 0;

    // Mostrar el total de ahorros en la tarjeta
    const ahorrosStat = document.getElementById("ahorros-stat");
    if (ahorrosStat) {
        ahorrosStat.textContent = `$${Number(totalAhorros).toLocaleString()}`;
    }

    // Renderizar el gráfico solo si hay datos
    const chartAhorrosDiv = document.getElementById("chartAhorros");
    if (chartAhorrosDiv) {
        if (ahorroData.length > 0 && ahorroData.some((val) => val > 0)) {
            const chartAhorros = new ApexCharts(chartAhorrosDiv, {
                chart: {
                    type: "donut",
                    height: 320,
                    background: "#fff",
                },
                series: ahorroData,
                labels: ahorroLabels,
                legend: {
                    position: "bottom",
                    labels: { colors: "#333" },
                },
                colors: [
                    "#4CAF50",
                    "#2196F3",
                    "#FFC107",
                    "#FF5722",
                    "#9C27B0",
                    "#00BCD4",
                ],
                dataLabels: {
                    enabled: true,
                    formatter: function (val, opts) {
                        return opts.w.config.series[opts.seriesIndex] > 0
                            ? opts.w.config.series[opts.seriesIndex]
                            : "";
                    },
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return `$${val.toLocaleString()}`;
                        },
                    },
                },
            });
            chartAhorros.render();
        } else {
            chartAhorrosDiv.innerHTML = `<div style="text-align:center;color:#888;padding:40px 0;">No tienes ahorros acumulados aún.</div>`;
        }
    }

    // Gráfico de gastos por categoría (treemap) con colores por rango de gravedad
    const treemapData = window.dashboardData?.treemapData || [];

// Define los colores por rango
function getColorByPorcentaje(p) {
    if (p >= 1.0)   return "#b71c1c"; // Rojo muy oscuro (100%+)
    if (p >= 0.9)   return "#d72638"; // Rojo fuerte (90-99%)
    if (p >= 0.7)   return "#ff9800"; // Naranja (70-89%)
    if (p >= 0.5)   return "#ffc107"; // Amarillo (50-69%)
    if (p >= 0.3)   return "#4caf50"; // Verde (30-49%)
    if (p >= 0.1)   return "#2196f3"; // Azul (10-29%)
    return "#90caf9";                 // Azul claro (<10%)
}

// Agrega el color a cada dato
const treemapDataWithColors = treemapData.map((item) => {
    const porcentaje = totalIngresos > 0 ? item.y / totalIngresos : 0;
    return {
        ...item,
        fillColor: getColorByPorcentaje(porcentaje),
    };
});

if (document.getElementById("treemap-gastos")) {
    const chartTreemap = new ApexCharts(
        document.querySelector("#treemap-gastos"),
        {
            chart: {
                type: "treemap",
                height: 350,
                background: "#fff",
            },
            series: [
                {
                    data: treemapDataWithColors,
                },
            ],
            legend: { show: false },
            title: { text: "" },
            // colors: treemapColors, // Ya no es necesario
            dataLabels: {
                enabled: true,
                style: {
                    fontSize: "14px",
                    fontWeight: "bold",
                    colors: ["#333"],
                },
                formatter: function (text, op) {
                    return text + ": $" + op.value.toLocaleString();
                },
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return `$${val.toLocaleString()}`;
                    },
                },
            },
        }
    );
    chartTreemap.render();
}
});
