import { useEffect, useRef, useState } from "react"
import { Link } from "@inertiajs/react"
import api from "@/axios"
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout"
import { Head } from "@inertiajs/react"
import TaskFilters from "@/Components/TaskFilters"
import { useInfiniteQuery } from "@tanstack/react-query"
import useInfiniteScroll from "@/hooks/useInfiniteScroll"

export default function Index() {
  const [filters, setFilters] = useState({
    search: "",
    status: "",
  })

  const { data, fetchNextPage, hasNextPage, isFetchingNextPage, status } = useInfiniteQuery({
    queryKey: ["tasks", filters],
    queryFn: async ({ pageParam = 1 }) => {
      const filteredFilters = Object.fromEntries(
        Object.entries(filters).filter(([key, value]) => value !== ""),
      )

      const res = await api.get("/tasks", {
        params: {
          page: pageParam,
          ...filteredFilters,
        },
      })
      return res.data
    },
    getNextPageParam: (lastPageData) => {
      const lastPage = Math.ceil(lastPageData.total / lastPageData.per_page)

      if (lastPageData.current_page < lastPage) {
        return lastPageData.current_page + 1
      }

      return undefined
    },
  })

  const tasks = data?.pages.flatMap((page) => page.data) ?? []

  const loaderRef = useInfiniteScroll(() => {
    if (!isFetchingNextPage && hasNextPage) fetchNextPage()
  })

  return (
    <AuthenticatedLayout
      header={
        <h2 className="text-xl font-semibold leading-tight text-gray-800">List of your tasks</h2>
      }
    >
      <Head title="Tasks" />

      <div className="p-12">
        <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
          <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <Link
              href="/tasks/create"
              className="px-4 py-2 bg-blue-600 inline-block text-white rounded mt-4 mb-4 mx-4"
            >
              + Add Task
            </Link>{" "}
            <TaskFilters filters={filters} setFilters={setFilters} />
            {status === "loading" && <div className="p-4">Loading tasks…</div>}
            {tasks.map((task) => (
              <div key={task.id} className="p-4 border rounded">
                <h2 className="font-semibold text-lg">{task.title}</h2>
                <p className="text-sm text-gray-600">
                  Urgency: {task.urgency} | Impact: {task.impact} | Effort: {task.effort}
                </p>

                <div className="mt-2 flex gap-3">
                  <Link href={`/tasks/${task.id}/edit`} className="text-blue-600">
                    Edit
                  </Link>
                </div>
              </div>
            ))}
            {isFetchingNextPage && <div className="py-4 text-center">Loading more…</div>}
            <div ref={loaderRef} className="h-14"></div>
            {!hasNextPage && (
              <div className="px-4 py-6 text-center text-gray-500">No more tasks to load.</div>
            )}
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  )
}
