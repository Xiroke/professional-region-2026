/**
 * Проверяет находитлся ли курсор в фигуре
 * @param mx
 * @param my
 * @param shape
 * @returns {boolean}
 */
export const isCursorInShape = (mx, my, shape) => {
    return mx >= shape.x && mx <= shape.x + shape.w &&
        my >= shape.y && my <= shape.y + shape.h
}