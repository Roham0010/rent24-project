export const Step1 = ({ formData, setFormData }) => {
  return (
    <div class="input-group">
      <label class="input-group-text" for="inputGroupSelect01">
        City:
      </label>
      <select
        class="form-select"
        id="inputGroupSelect01"
        onChange={(e) => setFormData({ ...formData, city: e.target.value })}
      >
        <option selected={!formData?.city ? true : false}>Choose...</option>
        {[...Array(5).keys()].map((i) => (
          <option
            value={"city " + i}
            selected={formData?.city === "city " + i ? true : false}
          >
            City {i}
          </option>
        ))}
      </select>
    </div>
  );
};
