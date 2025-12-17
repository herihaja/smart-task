import { useState } from "react"
import { Link } from "@inertiajs/react"
import api from "@/axios"
import { router } from "@inertiajs/react"
import { useToast } from "@/Components/ToastProvider"

export default function TaskForm({ onSubmit, task = {}, errors = {}, isEdit = false }) {
  const [title, setTitle] = useState(task.title || "")
  const [description, setDescription] = useState(task.description || "")
  const [urgency, setUrgency] = useState(task.urgency || "medium")
  const [impact, setImpact] = useState(task.impact || "medium")
  const [effort, setEffort] = useState(task.effort || "medium")
  const [dueDate, setDueDate] = useState(task.due_date || "")
  const [completed, setCompleted] = useState(task.completed || false)
  const [toast, setToast] = useState(null)
  const { showToast } = useToast()

  function handleSubmit(e) {
    e.preventDefault()
    onSubmit({
      title,
      description,
      urgency,
      impact,
      effort,
      due_date: dueDate,
      completed,
    })
  }

  const deleteTask = (e) => {
    if (confirm(`Are you sure you want to delete this task?`)) {
      api.delete(`/tasks/${task.id}`).then(() => {
        router.visit("/tasks")
      })
    }
  }

  const inferAI = (e) => {
    api.post(`/tasks/ai/infer-score`, { title, description }).then((res) => {
      if (res?.data?.errors) return

      if (res.data.ai_used) {
        showToast("Task attributes inferred by AI", "success")
        const inferred = res.data
        setUrgency(inferred.urgency || urgency)
        setImpact(inferred.impact || impact)
        setEffort(inferred.effort || effort)
      } else {
        showToast("AI inference unavailable.", "info")
      }
    })
  }

  return (
    <form onSubmit={handleSubmit} className="p-6 space-y-4">
      {/* Title */}
      <div>
        <label className="block font-medium">Title</label>
        <input
          type="text"
          value={title}
          onChange={(e) => setTitle(e.target.value)}
          className="border rounded w-full p-2"
        />
        {errors.title && <p className="text-red-600 text-sm">{errors.title}</p>}
      </div>

      {/* Description */}
      <div>
        <label className="block font-medium">Description</label>
        <textarea
          value={description}
          onChange={(e) => setDescription(e.target.value)}
          className="border rounded w-full p-2 h-28"
        />
        {errors.description && <p className="text-red-600 text-sm">{errors.description}</p>}
      </div>

      {/* Enums */}
      <div className="grid grid-cols-3 gap-4">
        <div>
          <label className="block font-medium">Urgency</label>
          <select
            value={urgency}
            onChange={(e) => setUrgency(e.target.value)}
            className="border rounded w-full p-2"
          >
            <option value="low">Low</option>
            <option value="medium">Medium</option>
            <option value="high">High</option>
          </select>
          {errors.urgency && <p className="text-red-600 text-sm">{errors.urgency}</p>}
        </div>

        <div>
          <label className="block font-medium">Impact</label>
          <select
            value={impact}
            onChange={(e) => setImpact(e.target.value)}
            className="border rounded w-full p-2"
          >
            <option value="low">Low</option>
            <option value="medium">Medium</option>
            <option value="high">High</option>
          </select>
          {errors.impact && <p className="text-red-600 text-sm">{errors.impact}</p>}
        </div>

        <div>
          <label className="block font-medium">Effort</label>
          <select
            value={effort}
            onChange={(e) => setEffort(e.target.value)}
            className="border rounded w-full p-2"
          >
            <option value="low">Low</option>
            <option value="medium">Medium</option>
            <option value="high">High</option>
          </select>
          {errors.effort && <p className="text-red-600 text-sm">{errors.effort}</p>}
        </div>
      </div>

      {/* Due Date */}
      <div>
        <label className="block font-medium">Due Date</label>
        <input
          type="date"
          value={dueDate}
          onChange={(e) => setDueDate(e.target.value)}
          className="border rounded w-full p-2"
        />
        {errors.due_date && <p className="text-red-600 text-sm">{errors.due_date}</p>}
      </div>

      {/* Completed checkbox only for EDIT */}
      {isEdit && (
        <div className="flex items-center gap-2">
          <input
            type="checkbox"
            checked={completed}
            onChange={(e) => setCompleted(e.target.checked)}
          />
          <label className="font-medium">Completed</label>
        </div>
      )}

      <div className="flex items-center gap-4 mt-6">
        <button type="submit" className="px-4 py-2 bg-blue-600 text-white rounded">
          Save
        </button>

        {isEdit && (
          <>
            <button
              type="button"
              className="px-4 py-2 bg-red-600 text-white rounded"
              onClick={deleteTask}
            >
              Delete
            </button>

            <button
              type="button"
              className="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded"
              onClick={inferAI}
            >
              ✨ Infer with AI
            </button>
          </>
        )}
        <Link href="/tasks" className="text-gray-600 hover:underline">
          Cancel
        </Link>
      </div>
    </form>
  )
}
