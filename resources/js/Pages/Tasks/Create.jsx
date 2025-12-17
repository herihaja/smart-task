import { Head, router, usePage } from "@inertiajs/react"
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout"
import TaskForm from "./TaskForm"
import api from "@/axios"
import { useToast } from "@/Components/ToastProvider"

export default function Create() {
  const { errors } = usePage().props
  const { showToast } = useToast()

  const handleSubmit = (data) => {
    api.post("/tasks", data).then((res) => {
      if (res.status === 201) {
        showToast("Task created successfully!", "success")
        router.visit("/tasks")
      }
    })
  }

  return (
    <AuthenticatedLayout
      header={<h2 className="text-xl font-semibold leading-tight text-gray-800">Create Task</h2>}
    >
      <Head title="Create Task" />

      <div className="p-12">
        <div className="max-w-4xl mx-auto bg-white shadow-sm sm:rounded-lg">
          <TaskForm onSubmit={handleSubmit} errors={errors} />
        </div>
      </div>
    </AuthenticatedLayout>
  )
}
