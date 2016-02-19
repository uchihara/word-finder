class Cell
  attr_reader :char

  def initialize char
    @char = char
    unmark!
  end

  def marked?
    @marked
  end

  def mark!
    @marked = true
  end

  def unmark!
    @marked = false
  end
end

