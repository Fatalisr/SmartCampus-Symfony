/*    <script>
        // Dimensions du graphique
        margin = {top: 10, right: 40, bottom: 30, left: 30};
        width = document.getElementById("pSal").offsetWidth - margin.left - margin.right;   // Largeur de l'elt
        height = 400 - margin.top - margin.bottom;                                          // Hauteur de l'elt

        // Crée l'image dans la balise avec id="graph"
        graph = d3.select("#graph").append("svg")
            .attr("width", width)
            .attr("height", height + margin.top + margin.bottom)
            .append("g")
            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

        // Jeu de données test
        data = [ {x:5, y:20}, {x:10, y:50}, {x:15, y:50}, {x:20, y:40}, {x:25, y:42}, {x:30, y:43}, {x:35, y:47}, {x:40, y:45}, {x:45, y:34}, {x:50, y:38}, {x:55, y:45}, {x:60, y:50} ]

        // Axe des X
        x = d3.scaleLinear([0,d3.max(data,d => d.x)],[0,288]);      // 1er param = domaine et 2eme = range
        graph.append('g').attr("transform", "translate(0," + height + ")").call(d3.axisBottom(x));

        // Y scale and Axis
        y = d3.scaleLinear([0,d3.max(data,d => d.y)],[height, 0]);
        graph.append('g')
            .call(d3.axisLeft(y));

        // Add 3 dots for 0, 50 and 100%
        graph.selectAll("whatever")
            .data(data)
            .enter()
            .append("circle")
            .attr("cx", function(d){ return x(d.x) })
            .attr("cy", function(d){ return y(d.y) })
            .attr("r", 5)
        graph.append("path")
            .attr("fill", "none")
            .attr("stroke", "steelblue")
            .attr("stroke-width", 1.5)
            .attr("d", line(data));
        <!--d3js.org/what-is-d3-->
    </script>*/

/*<script>
    // Dimensions du graphique
    margin = {top: 10, right: 40, bottom: 30, left: 30};
    width = document.getElementById("pSal").offsetWidth - margin.left - margin.right;   // Largeur de l'elt
    height = 400 - margin.top - margin.bottom;                                          // Hauteur de l'elt

    // Crée l'image dans la balise avec id="graph"
    graph = d3.select("#graph").append("svg")
    .attr("width", width)
    .attr("height", height + margin.top + margin.bottom)
    .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

    // Jeu de données test
    data = [ {x:5, y:20}, {x:10, y:50}, {x:15, y:50}, {x:20, y:40}, {x:25, y:42}, {x:30, y:43}, {x:35, y:47}, {x:40, y:45}, {x:45, y:34}, {x:50, y:38}, {x:55, y:45}, {x:60, y:50} ]

    // Axe des X
    x = d3.scaleLinear()      // 1er param = domaine et 2eme = range
    .domain([0,60])
    .range([ 0, width ]);
    graph.append('g').attr("transform", "translate(0," + height + ")").call(d3.axisBottom(x));

    // Axe des Y
    y = d3.scaleLinear()
    .domain([0,60])
    .range([ height, 0 ]);
    graph.append('g')
    .call(d3.axisLeft(y));

    line = d3.line()
    .x(function(data) { return x(data.date); })
    .y(function(data) { return y(data.close); });
    graph.append("g")
    .call(d3.axisLeft(y))
    .append("text")
    .attr("fill", "#000")
    .attr("transform", "rotate(-90)")
    .attr("y", 6)
    .attr("dy", "0.71em")
    .attr("text-anchor", "end")
    .text("Price ($)");
    graph.datum(data)
    .append("path")
    .attr("fill", "none")
    .attr("stroke", "steelblue")
    .attr("stroke-width", 15)
    .attr("data", line)
    <!--d3js.org/what-is-d3-->
</script>*/