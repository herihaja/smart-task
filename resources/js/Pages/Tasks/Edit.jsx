import { Head, router, usePage } from "@inertiajs/react"
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout"
import TaskForm from "./TaskForm"
import api from "@/axios"
import { useState } from "react"
import { useToast } from "@/Components/ToastProvider"

export default function Edit({ task }) {
  const { errors } = usePage().props
  const { showToast } = useToast()

  const handleSubmit = (data) => {
    api.put(`/tasks/${task.data.id}`, data).then((res) => {
      if (res?.data?.errors) return

      showToast("Task updated successfully!", "success")

      router.visit("/tasks")
    })
  }

  return (
    <AuthenticatedLayout
      header={<h2 className="text-xl font-semibold text-gray-800">Edit Task</h2>}
    >
      <Head title="Edit Task" />

      <div className="p-12">
        <div className="max-w-4xl mx-auto bg-white shadow-sm sm:rounded-lg">
          <TaskForm task={task.data} onSubmit={handleSubmit} errors={errors} isEdit={true} />
        </div>
      </div>
    </AuthenticatedLayout>
  )
}
