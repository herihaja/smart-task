import { useEffect, useState } from "react";

const Toast = ({ message, type = "success", duration = 4000, onClose }) => {
    const [closing, setClosing] = useState(false);

    useEffect(() => {
        const timer = setTimeout(() => {
            // 1. Start closing animation
            setClosing(true);

            // 2. After animation finishes, unmount
            const removeTimer = setTimeout(() => {
                onClose();
            }, 1000); // must match CSS duration

            return () => clearTimeout(removeTimer);
        }, duration);

        return () => clearTimeout(timer);
    }, [duration, onClose]);

    const colors = {
        success: "bg-green-100 text-green-800 border-green-300",
        error: "bg-red-100 text-red-800 border-red-300",
        warning: "bg-yellow-100 text-yellow-800 border-yellow-300",
        info: "bg-blue-100 text-blue-800 border-blue-300",
    };

    return (
        <div
            className={`fixed top-5 right-5 px-4 py-2 rounded shadow border z-50 ${
                colors[type]
            } ${closing ? "animate-slide-out" : "animate-slide-in"}`}
        >
            {message}
        </div>
    );
}

export default Toast;
