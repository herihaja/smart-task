import { useEffect, useRef } from "react";

export default function useInfiniteScroll(onIntersect) {
    const loaderRef = useRef(null);

    useEffect(() => {
        if (!loaderRef.current) return;

        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting) {
                onIntersect();
            }
        });

        observer.observe(loaderRef.current);

        return () => observer.disconnect();
    }, [onIntersect]);

    return loaderRef;
}
