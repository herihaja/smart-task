import { createContext, useContext, useState, useCallback } from "react"
import Toast from "./Toast"

const ToastContext = createContext()

const ToastProvider = ({ children }) => {
  const [toast, setToast] = useState(null)

  const showToast = useCallback((message, type = "success", duration = 4000) => {
    setToast({ message, type, duration })
  }, [])

  const closeToast = () => setToast(null)

  return (
    <ToastContext.Provider value={{ showToast }}>
      {children}

      {toast && (
        <Toast
          message={toast.message}
          type={toast.type}
          duration={toast.duration}
          onClose={closeToast}
        />
      )}
    </ToastContext.Provider>
  )
}

export default ToastProvider

export function useToast() {
  return useContext(ToastContext)
}
