const getCanvasDefaults = (canvas) => {
    if (!canvas) {
        throw new Error("Canvas is required to initialize defaults.");
    }

    return {
        top_text: {
            fontSize: 30,
            fill: "#000000",
            fontFamily: "Arial",
            originX: "center",
            hasControls: true,
            editable: true,
            stay: true,
        },

        bottom_text: {
            fontSize: 30,
            fill: "#000000",
            fontFamily: "Arial",
            originX: "center",
            hasControls: true,
            editable: true,
            stay: true,
        },

        shirt: {
            box: {
                // strokeWidth: 2,
                strokeDashArray: [5, 5],
                fill: "transparent",
                // stroke: "#ccc",
                selectable: false,
                evented: false,
                stay: true,
                stay_when_pos: true,
                left: canvas.width / 2,
                top: canvas.height / 2,
                width: canvas.width * 0.4,
                height: canvas.height * 0.3,
                originX: "center",
                originY: "center",
            },
        },

        hat: {
            box: {
                // strokeWidth: 2,
                strokeDashArray: [5, 5],
                fill: "transparent",
                // stroke: "#ccc",
                selectable: false,
                evented: false,
                stay: true,
                stay_when_pos: true,
                left: canvas.width / 2,
                top: canvas.height / 2,
                width: canvas.width * 0.4,
                height: canvas.height * 0.2,
                originX: "center",
                originY: "center",
            },
        },
    };
};

export default getCanvasDefaults;
