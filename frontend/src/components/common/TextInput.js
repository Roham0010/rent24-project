export const TextInput = ({ name, label, value, onChange, error }) => {
  return (
    <div class="mb-3">
      <label for={name} class="form-label">
        {label}
      </label>
      <input
        onChange={onChange}
        value={value}
        type={name}
        name={name}
        class="form-control"
        id={name}
        required
        aria-describedby={name + "Help"}
      />
      {!!error && (
        <div id={name + "Help"} class="form-text">
          {error}
        </div>
      )}
    </div>
  );
};
