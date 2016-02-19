require 'set'
require_relative 'matrix'
require_relative 'string_walker'
require_relative 'dict'

class WordFinder
  def initialize matrix, lengths, use_dict
    @matrix = Matrix.new matrix
    @lengths = lengths
    @use_dict = use_dict
  end

  def find_words
    scanner = StringWalker.new @matrix
    results = Set.new
    (0...@matrix.x_max).each do |x|
      (0...@matrix.y_max).each do |y|
        @lengths.each do |length|
          hits = scanner.scan(length, x, y)
          if @use_dict
            dict = Dict.new(length)
            results.merge hits & dict.dict
          else
            results.merge hits
          end
        end
      end
    end
    results.sort.to_a
  end
end


