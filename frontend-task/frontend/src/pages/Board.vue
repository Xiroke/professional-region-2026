<script setup>
import {onMounted, ref, useTemplateRef} from "vue";
import { isCursorInShape } from "../utils.js";
import {useRoute, useRouter} from 'vue-router'
import Input from "../components/Input.vue";

const isOwner = ref(false)
const route = useRoute()
const canvas = useTemplateRef('canvas');
let ctx = null;

const HANDLE_SIZE = 20;
const DEFAULT_SIZE = 150;

let anchorX = 0;
let anchorY = 0;
let dragOffset = { x: 0, y: 0 };

const textInput = ref()
const blockedShapesId = ref({})
const shapes = ref([]);
const selectedShapeId = ref(null);
const isDragging = ref(false);
const isResizing = ref(false);
const image = ref()

const TOOLS = {
  RECTANGLE: 'rectangle',
  CIRCLE: 'circle',
  LINE: 'line',
  IMAGE: 'image',
  TEXT: 'text',
};

const WEBSOCKET_EVENT = {
  CREATE_ELEMENT: 'create_element',
  LOAD_SHAPES: 'load_shapes',
  MOVE: 'move',
  RESIZE: 'resize',
  FOCUS: 'focus',
  UNFOCUS: 'unfocus'
}

// --- WebSocket ---
let boardHash = null;
const socket = new WebSocket('ws://localhost:8080');

socket.onopen = () => {
  if (route?.params?.hash) {
    socket.send(JSON.stringify({ action: 'join', payload: {hash: route.params.hash} }));
  } else {
    socket.send(JSON.stringify({ action: 'create' }));
  }
}
socket.onmessage = (event) => {
  const data = JSON.parse(event.data);
  if (data.action === 'created') {
    boardHash = data.hash;
    isOwner.value = true
  }

  if (data.action === 'joined') {
    console.log('joined')
    if (!isOwner.value) {
      return
    }
    socket.send(JSON.stringify({action: 'message', payload: {event: WEBSOCKET_EVENT.LOAD_SHAPES, shapes: shapes.value}}))
  }

  if (data.action === 'message') {
    const payload = data.data
    if (payload.event === WEBSOCKET_EVENT.CREATE_ELEMENT) {
      shapes.value.push(payload.shape);
      redraw();
    }
    if (payload.event === WEBSOCKET_EVENT.LOAD_SHAPES) {
      shapes.value = payload.shapes
      redraw();
    }
    if (payload.event === WEBSOCKET_EVENT.MOVE) {
      const shapeIndex = shapes.value.findIndex(shape => shape.id === payload.shape_id)
      shapes.value[shapeIndex].x = payload.x
      shapes.value[shapeIndex].y = payload.y
      redraw()
    }
    if (payload.event === WEBSOCKET_EVENT.RESIZE) {
      const shapeIndex = shapes.value.findIndex(shape => shape.id === payload.shape_id)
      shapes.value[shapeIndex].x = payload.x
      shapes.value[shapeIndex].y = payload.y
      shapes.value[shapeIndex].w = payload.w
      shapes.value[shapeIndex].h = payload.h
      redraw()
    }
    if (payload.event === WEBSOCKET_EVENT.FOCUS) {
      blockedShapesId.value[payload.shape_id] = 'Инкогнито'
      redraw()
    }
    if (payload.event === WEBSOCKET_EVENT.UNFOCUS) {
      delete blockedShapesId.value[payload.shape_id]
      redraw()
    }
  }
};

// --- Отрисовка ---
const drawShape = (ctx, shape, isSelected, focusedBy) => {
  ctx.beginPath();
  ctx.fillStyle = isSelected ? '#666' : 'black';

  if (shape.type === TOOLS.RECTANGLE) {
    ctx.fillRect(shape.x, shape.y, shape.w, shape.h);
  }
  if (shape.type === TOOLS.CIRCLE) {
    const radius = Math.abs(shape.w / 2);
    ctx.arc(shape.x + radius, shape.y + radius, radius, 0, Math.PI * 2);
    ctx.fill();
  }
  if (shape.type === TOOLS.LINE) {
    ctx.fillRect(shape.x, shape.y, shape.w, shape.h);
  }
  if (shape.type === TOOLS.TEXT) {
    ctx.font = `${shape.h}px serif`;
    ctx.fillText(shape.addition.text , shape.x, shape.y + shape.h / 2);
  }
  if (shape.type === TOOLS.IMAGE) {
    const img = new Image();
    img.src = shape.addition.image;
    img.onload = () => {
      ctx.drawImage(img, shape.x, shape.y, shape.w, shape.h);
    };
  }

  if (blockedShapesId.value?.[shape.id]) {
    ctx.font = `24px serif`;
    ctx.fillText(blockedShapesId.value[shape.id], shape.x, shape.y - 12);
  }

  if (isSelected) {
    ctx.fillStyle = "#00f";
    // Маркер ресайза справа снизу
    ctx.fillRect(shape.x + shape.w - HANDLE_SIZE, shape.y + shape.h - HANDLE_SIZE, HANDLE_SIZE, HANDLE_SIZE);
  }
};

const redraw = () => {
  if (!ctx) return;
  ctx.clearRect(0, 0, canvas.value.width, canvas.value.height);
  shapes.value.forEach(s => drawShape(ctx, s, selectedShapeId.value === s.id));
};

// --- Логика создания ---
const createShape = (type, addition) => {
  if (!canvas.value) return;

  const newShape = {
    id: crypto.randomUUID(),
    x: (canvas.value.width / 2) - (DEFAULT_SIZE / 2),
    y: (canvas.value.height / 2) - (DEFAULT_SIZE / 2),
    w: type === TOOLS.IMAGE ? addition.w : type === TOOLS.LINE ? 300 : DEFAULT_SIZE,
    h: type === TOOLS.IMAGE ? addition.h : type === TOOLS.LINE ? 4 : DEFAULT_SIZE,
    type,
    addition
  };

  shapes.value.push(newShape);
  socket.send(JSON.stringify({action: 'message', payload: {event: WEBSOCKET_EVENT.CREATE_ELEMENT, shape: newShape}}))
  selectedShapeId.value = newShape.id;
  redraw();
};

// --- Обработчики мыши ---
const handleMouseDown = (e) => {
  const mouseX = e.offsetX;
  const mouseY = e.offsetY;

  const clickedShape = [...shapes.value].reverse().find(s => isCursorInShape(mouseX, mouseY, s));

  if (clickedShape) {
    if (blockedShapesId.value?.[clickedShape.id]) {
      return
    }

    socket.send(JSON.stringify({action: 'message', payload: {event: WEBSOCKET_EVENT.FOCUS, shape_id: clickedShape.id}}))
    selectedShapeId.value = clickedShape.id;

    // Проверка на ресайз
    const isOnHandle = mouseX >= clickedShape.x + clickedShape.w - HANDLE_SIZE &&
        mouseY >= clickedShape.y + clickedShape.h - HANDLE_SIZE;

    if (isOnHandle) {
      isResizing.value = true;
      anchorX = clickedShape.x;
      anchorY = clickedShape.y;
    } else {
      isDragging.value = true;
      dragOffset = { x: mouseX - clickedShape.x, y: mouseY - clickedShape.y };
    }
  } else {
    if (selectedShapeId.value && (!clickedShape || clickedShape.id !== selectedShapeId.value)) {
      socket.send(JSON.stringify({
        action: 'message',
        payload: { event: WEBSOCKET_EVENT.UNFOCUS, shape_id: selectedShapeId.value }
      }));
    }
    selectedShapeId.value = null;
  }
  redraw();
};

const handleMouseMove = (e) => {
  if (!selectedShapeId.value) return;
  const shape = shapes.value.find(s => s.id === selectedShapeId.value);
  const mouseX = e.offsetX;
  const mouseY = e.offsetY;

  if (isResizing.value) {
    const shape_x = Math.min(mouseX, anchorX);
    const shape_y = Math.min(mouseY, anchorY);
    const shape_w = Math.abs(mouseX - anchorX);
    const shape_h = shape.type === TOOLS.IMAGE ? shape_w * shape.h / shape.w : Math.abs(mouseY - anchorY);
    shape.x = shape_x;
    shape.y = shape_y;
    shape.w = shape_w;
    shape.h = shape_h;
    redraw();
    socket.send(JSON.stringify({action: 'message',
      payload: {
        event: WEBSOCKET_EVENT.RESIZE,
        shape_id: shape.id,
        x: shape_x,
        y: shape_y,
        w: shape_w,
        h: shape_h,
      }
    }))
  } else if (isDragging.value) {
    shape.x = mouseX - dragOffset.x;
    shape.y = mouseY - dragOffset.y;
    redraw();
    socket.send(JSON.stringify({action: 'message',
      payload: {
        event: WEBSOCKET_EVENT.MOVE,
        shape_id: shape.id,
        x: mouseX - dragOffset.x,
        y: mouseY - dragOffset.y
      }
    }))
  }
};

const handleMouseUp = () => {
  isResizing.value = false;
  isDragging.value = false;
};

const shareBoard = () => {
  alert(window.location.origin + '/board/' + boardHash)
}

const createText = () => {
  createShape(TOOLS.TEXT, {text: textInput.value})
}
const createImage = (e) => {
  const image = e.target.files[0]
  const reader = new FileReader();

  reader.onload = () => {
    const base64Image = reader.result
    const img = new Image();
    img.src = base64Image;

    img.onload = () => {
      const width = img.naturalWidth;
      const height = img.naturalHeight;

      createShape(TOOLS.IMAGE, {
        image: base64Image,
        w: width,
        h: height
      })
    }
  }

  reader.readAsDataURL(image);
}

onMounted(() => {
  ctx = canvas.value?.getContext('2d');
});
</script>

<template>
  <section class="board-container">
    <div class="relative">
      <canvas id="canvas" ref="canvas" width="1600" height="900"
              @mousedown="handleMouseDown"
              @mousemove="handleMouseMove"
              @mouseup="handleMouseUp"
              @mouseleave="handleMouseUp"
      ></canvas>
      <div class="tools">
        <button class="btn" @click="() => createShape(TOOLS.RECTANGLE)">Прямоугольник</button>
        <button class="btn" @click="() => createShape(TOOLS.CIRCLE)">Круг</button>
        <button class="btn" @click="() => createShape(TOOLS.LINE)">Линия</button>
        <Input class="btn" @change="createImage" type="file" />
        <div class="input-group">
          <Input placeholder="Текст" v-model="textInput" />
          <button class="btn" @click="createText">Добавить</button>
        </div>

        <button class="btn btn-share" @click="shareBoard">Поделиться</button>
      </div>
    </div>
  </section>
</template>

<style scoped>
.board-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
}
#canvas {
  border: 1px solid #cacaca;
  background: #f9f9f9;
}
.tools {
  gap: 8px;
  display: flex;
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
  bottom: 20px;
}
.btn { padding: 8px 16px; cursor: pointer; }
.btn-share { background: #4caf50; color: white; border: none; }
</style>