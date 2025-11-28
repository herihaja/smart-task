import React from "react";

const TaskFilters = ({ filters, setFilters }) => {
  const handleChange = (key, value) => {
    setFilters(prev => ({ ...prev, [key]: value }));
  };

  const inputClass = "peer block w-full px-3 pt-5 pb-2 border rounded-md text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500";
  const labelClass = "absolute left-3 top-2.5 text-gray-400 text-sm transition-all peer-placeholder-shown:top-5 peer-placeholder-shown:text-gray-400 peer-placeholder-shown:text-base peer-focus:top-2.5 peer-focus:text-sm peer-focus:text-blue-500";

  return (
    <div className="mb-4 p-4 bg-white border rounded flex flex-wrap gap-4 items-end">

      {/* Search */}
      <div className="relative w-64">
        <input
          type="text"
          value={filters.search}
          onChange={e => handleChange("search", e.target.value)}
          placeholder=" "
          className={inputClass}
        />
        <label className={labelClass}>Search tasks...</label>
      </div>

      {/* Urgency */}
      <div className="relative w-36">
        <select
          value={filters.urgency}
          onChange={e => handleChange("urgency", e.target.value)}
          className={inputClass + " bg-white appearance-none"}
        >
          <option value=""></option>
          <option value="low">Low</option>
          <option value="medium">Medium</option>
          <option value="high">High</option>
        </select>
        <label className={labelClass}>Urgency</label>
      </div>

      {/* Impact */}
      <div className="relative w-36">
        <select
          value={filters.impact}
          onChange={e => handleChange("impact", e.target.value)}
          className={inputClass + " bg-white appearance-none"}
        >
          <option value=""></option>
          <option value="low">Low</option>
          <option value="medium">Medium</option>
          <option value="high">High</option>
        </select>
        <label className={labelClass}>Impact</label>
      </div>

      {/* Effort */}
      <div className="relative w-36">
        <select
          value={filters.effort}
          onChange={e => handleChange("effort", e.target.value)}
          className={inputClass + " bg-white appearance-none"}
        >
          <option value=""></option>
          <option value="low">Low</option>
          <option value="medium">Medium</option>
          <option value="high">High</option>
        </select>
        <label className={labelClass}>Effort</label>
      </div>

      {/* Reset button */}
      <button
        onClick={() => setFilters({ search: "", urgency: "", effort: "", impact: "" })}
        className="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 self-center"
      >
        Reset
      </button>

    </div>
  );
};

export default TaskFilters;
